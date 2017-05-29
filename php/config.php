<?php
include_once "manageLogs.php";

class Config{

	const THUMBS = "thumbs";
	const LARGE = "large";
	const UPLOAD = "upload";
	const FULLSIZE = "fullsize";
	const XML_CATALOG = "xmlCatalog";
	const NBCOLS = "nbCols";
	const NBFILES = "nbFiles";
	const BCK = "background";

	
	private $_upload;
	private $_large;
	private $_thumbs;
	private $_fullsize;
	private $_xmlCatalog;
	private $_nbCols;
	private $_nbFiles;
	private $_background;

	
	public function __construct($folder, $configName){

		//TODO gérer l'impossibibilité d'accéder au fichier de config : message html indiquant que le compte n'est pas accessible

		$json = file_get_contents($folder.$configName);	
		$config = json_decode($json, true);
		$pos = strpos($folder,"usr");
		$relativeFolder = substr($folder,strpos($folder,"usr"));
	
		if($config==null){
			return null;
		}
	
		$this->_upload = $relativeFolder.$config[config::UPLOAD]."/";
		$this->_large = $relativeFolder.$config[config::LARGE]."/";
		$this->_thumbs =$relativeFolder.$config[config::THUMBS]."/";
		$this->_fullsize = $relativeFolder.$config[config::FULLSIZE]."/";
		$this->_xmlCatalog = $folder.$config[config::XML_CATALOG];
		$this->_nbCols = $config[config::NBCOLS];
		$this->_nbFiles = $config[config::NBFILES];
		$this->_background = $config[config::BCK];


	}
	
	public function upload(){
		return $this->_upload;
	}
	
	public function large(){
		return $this->_large;
	}
	
	public function thumbs(){
		return $this->_thumbs;
	}
	
	public function fullsize(){
		return $this->_fullsize;
	}
	
	public function xmlCatalog(){
		return $this->_xmlCatalog;
	}
	
	public function nbCols(){
		if($this->_nbCols>0 && $this->_nbCols<6){
			return $this->_nbCols;
		}
		return 3;
	}
	
	public function nbFiles(){
		return $this->_nbFiles;
	}
	
	public function background(){
		return $this->_background;
	}
	


}
	
	
?>