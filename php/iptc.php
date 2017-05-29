<?php
	
include_once 'manageLogs.php';

/*IPTC class manages IPTC metadata of jpeg images.
*it needs the url of the image and then looks at available informations
*if all or part of the IPTC data are not available, they are set to null
*/
class IPTC{
	
	private $_meta;
	private $_url;
	private $_date;
	private $_time;
	private $_keywords;
	private $_title;
	
	const DATE_FORMAT="Y-m-d-H:i:s";
	
	private $_log;
	
	public function __construct($url){

		//create logger to output message in a txt file
		$this->_log = new ManageLogs();
		
		$this->_url=$url;
		
		getImageSize($url,$info);
		if (!isset($info["APP13"])){
			$this->_log->addLog("no IPTC for ".$url);
			$this->_date = null;
			$this->_time = null;
			$this->_title = null;
			$this->_keywords = null;
			return;
		}
		
		$this->_log->addLog("IPTC found for ".$url);
		
		$meta = iptcparse($info["APP13"]);
		
		//catch the date of creation
		$date = $meta["2#055"][0];
		//date should be at least 8 caracter long for the correct executions of date fuctions below
		if (strlen($date)<8){
			$this->_log->addLog("date is not correctly set");
			$date = null;
		}
		
		//catch the time of creation
		$time = $meta["2#060"][0];
		//time should be at least 6 caracter long for the correct executions of date fuctions below
		if (strlen($time)<6){
			$this->_log->addLog("time is not correctly set");
			$time = null;
		}
		
		//catch the keywords
		$keywords = $meta["2#025"][0];
		
		//catch the title
		$title = $meta["2#105"][0];
		
		//instanciate the global variables
		$this->_meta=$meta;
		$this->_date=$date;		
		$this->_time=$time;
		$this->_keywords=$keywords;
		$this->_title=$title;
	}
	
	//get url of file associated with this IPTC
	public function url(){
		return $this->_url;
	}
	
	//get keywords
	public function keywords(){
		return  $this->_keywords;
	}
	
	//get title
	public function title(){
		return $this->_title;
	}	
	
	//get Date of creation
	public function getDate(){
		
		if($this->_date==null || $this->_time==null){
			return null;
		}
		
		$maDate = $this->getYear()."-".$this->getMonth()."-".$this->getDay()."-".$this->getHour().":".$this->getMinute().":".$this->getSecond();
		$date = DateTime::createFromFormat(IPTC::DATE_FORMAT,$maDate);

		return $date;
	}	
	
	
	private function getYear(){
		return substr($this->_date,0,4);		
	}
	
	private function getMonth(){
		return substr($this->_date,4,2);		
	}
	
	private function getDay(){
		return substr($this->_date,6,2);		
	}
	
	private function getHour(){
		return substr($this->_time,0,2);		
	}
	
	private function getMinute(){
		return substr($this->_time,2,2);		
	}
	
	private function getSecond(){
		return substr($this->_time,4,2);		
	}
	

}

?>