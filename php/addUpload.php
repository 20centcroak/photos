<?php

include_once "manageLogs.php";
include_once "manageFilesAndDirectories.php";
include_once "XMLCat.php";
include_once "XMLAccount.php";
include_once "Jpeg.php";

/*AddUpload class manages images in the upload folder of the given user. 
*the construction of this objects starts the following process:
*	-reads the config file of the user to get the url of the different folders
*	-check the content of the upload file and start processing the images if some are presents
*	-creates thumbnails and large images in the dedicated folders
*	-moves the original image in the fullsize folder
*	-updates the xml image catalog, putting new images in a chronological sorting
*/
class AddUpload{
	
	//defines the dimensions of the thumbnails and the displayed (large) images
	const THUMB_WIDTH = 600;
	const LARGE_WIDTH = 1920;
	const LARGE_HEIGHT = 1080;
	
	private $_log;
	private $_config;
	private $_folder;	
	
	public function __construct($user){
		$this->_log = new ManageLogs();
		$this->_log->dlog("AddUpload");
		
		$this->_folder = $user->getDirectory();
		$this->_log->dlog("user folder: ".$this->_folder);
		$config = $user->config();
		$this->_log->dlog("config url: ".$this->_folder.$config);
		$json = file_get_contents($this->_folder.$config);
		$this->_config = json_decode($json, true);
		
		$this->add();
	}
	
	//add images to the catalog if present in the upload folder
	private function add(){
		$imgFiles = $this->find();

		if($imgFiles==null || count($imgFiles)==0){
			return "no upload";
		}
		
		$iptcs = $this->getIptc($imgFiles);
		$xmlCat = XMLCat::xmlCatFactory($this->_folder);
		
		if($xmlCat==null){
			$this->_log->dlog("catalog unavailable");
			return "catalog unavailable";
		}
				
		foreach($iptcs as $value){
		
			$this->_log->dlog("add ".$value->url());
			
			if($this->resize($value->url())){
				if($this->move($value->url())){
					$xmlCat->addImage($value);
				}
				else{
					$message=$message." ; moving failed on image ".$value->url();	
				}
			}
			else {
				$message=$message." ; resize failed on image ".$value->url();
			}		
		}
		
		$xmlCat->save();
		
		$this->_log->addlog($message);
		return $message;

	}
	
	//find new images in the upload folder
	private function find(){
		//get the directory url from the config file . Uploaded images should contain IPTC data		
		$dir = $this->_folder.$this->_config["upload"];
		$this->_log->dlog("AddUpload - directory explored ".$dir);	
		
		//wrong directory in config file
		if (!file_exists($dir)) {
			$this->_log->addLog("directory does not exist");
			return null;
		}
		
		//list jpg files in the directory
		return ManageFilesAndDirectories::getImgFiles($dir);
	}
	
	//constructs the IPTC object for each img file contained in $imgFiles
	private function getIptc($imgFiles){
		$nbImg = count($imgFiles);
		$this->_log->addlog("getIPTC :".$nbImg." files found");
		
		$tempIptcArray = array();
		$tempDateArray = array();
		
		for($i = 0; $i < $nbImg; $i++) {
			$tempIptcArray[$i] = new IPTC($imgFiles[$i]);			
		}
		
		foreach($tempIptcArray as $key=>$value) {
			$tempDateArray[$key] = $value->getDate();
			//$dateArray[$value->url()] = $value->getDate();
		}
		
		asort($tempDateArray);
		
		$iptcArray = array();
		$i = 0;
		foreach($tempDateArray as $key=>$value) {
			$iptcArray[$i] = $tempIptcArray[$key];
			$i++;
			//$dateArray[$value->url()] = $value->getDate();
		}
		
		$this->_log->addlog("images sorted by date");
		
		return $iptcArray;		
	}
	
	//resize the original image to create thumbnails and displayed (large) images
	private function resize($url){
		
		$filename = basename($url);
		$upload = $this->_folder.$this->_config['upload']."/".$filename;
		$large = $this->_folder.$this->_config['large']."/".$filename;
		$thumb = $this->_folder.$this->_config['thumbs']."/".$filename;
		
		$this->_log->addlog("create Thumbnail from ".$url." to ".$thumb);		
		if (Jpeg::resize_width(AddUpload::THUMB_WIDTH,$upload,$thumb)==false){
			return false;
		}
		
		$this->_log->addlog("create large image from ".$url." to ".$large);		
		if (Jpeg::resize_both(AddUpload::LARGE_WIDTH, AddUpload::LARGE_HEIGHT ,$upload,$large)==false){
			return false;
		}
		
		return true;		
	}	
	
	//move image to the fullsize folder
	private function move($url){
		
		$filename = basename($url);
		$dir = $this->_folder.$this->_config['fullsize']."/".$filename;
		
		$this->_log->addlog("moving file ".$url." to ".$dir);
		if (rename($url, $dir)){
			$this->_log->addlog("successfull");
			return true;
		}

		$this->_log->addlog("failed");
		return false;
	}

	
}
?>