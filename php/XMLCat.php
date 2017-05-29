<?php
include_once "manageLogs.php";
include_once "iptc.php";
include_once "config.php";

class XMLCat{
	
	const IMAGE_NODE="image";
	const CAT_NODE="catalog";
	const NAME_NODE="name";
	const DATE_NODE="created";
	const TITLE_NODE="title";
	const KEYWORD_NODE="keyword";	
	
	private $_config;
	private $_log;
	private $_xml;
	private $_dates;
	private $_title;
	private $_imgName;
	private $_largeUrl;
	private $_thumbUrl;
	private $_keywords;	
	
	private function __construct($xml, $config){
		//create logger to output message in a txt file
		$this->_log = new ManageLogs();	
		$this->_config=$config;
		$this->_xml=$xml;
		$this->makeArrays();		
	}
	
	public static function xmlCatFactory($config){
		
		$log = new ManageLogs();
		$log->dLog("xmlCatFactory");

		$xml = new DOMDocument("1.0", "utf-8");
		$xml->preserveWhiteSpace = false;
		$xml->formatOutput = true;
		
		$url = $config->xmlCatalog();
		$log->addlog("user catalog: ".$url);

		if (!file_exists($url)){
			$log->addlog("file does not exist");
			return null;
		}
		
		if(!$xml->load($url)) {
			$log->addlog("catalog can't be loaded");			
			return null;
		}

		return new XMLCat($xml, $config);
	}
	
	public static function createXMLCat(){
		$xml = new DOMDocument("1.0", "utf-8");
		$xml->preserveWhiteSpace = false;
		$xml->formatOutput = true;
		$catTag = $xml->createElement(XMLCat::CAT_NODE);
		$xml->appendChild($catTag);
		return $xml;
	}
	
	public static function saveXMLCat($xml, $url){
		$xml->save($url);	
	}
	
	public function save(){
		$this->_log->dlog("save catalog at address ".$this->_config.xmlCatalog());
		$this->_xml->save($this->_config.xmlCatalog());	
	}
	
	public function addImage($iptc){		
		$date = $iptc->getDate();
		$parent = $this->getParentNode($date);
		$this->createNode($iptc, $parent);
		$this->makeArrays();
	}

	private function createNode($iptc, $parent){
		
		//création du noeud XMLCat::DATE_NODE
		$dateTag = $this->_xml->createElement(XMLCat::DATE_NODE);
		$parent->appendChild($dateTag);
		$date = $iptc->getDate();
		$dateText = $this->_xml->createTextNode(date_format($date, IPTC::DATE_FORMAT));
		$dateTag->appendChild($dateText);
		
		//création du noeud XMLCat::NAME_NODE
		$nameTag = $this->_xml->createElement(XMLCat::NAME_NODE);
		$parent->appendChild($nameTag);
		$name = basename($iptc->url());
		$nameText = $this->_xml->createTextNode($name);
		$nameTag->appendChild($nameText);
		
		//création du noeud XMLCat::TITLE_NODE
		$titleTag = $this->_xml->createElement(XMLCat::TITLE_NODE);
		$parent->appendChild($titleTag);
		$title = $iptc->title();
		$titleText = $this->_xml->createTextNode($title);
		$titleTag->appendChild($titleText);
	}
	
	private function getParentNode($date){
		
		$this->_log->addlog("find parent");
		
		$nbDates = count($this->_dates);
		$this->_log->addlog($nbDates." dates");
		
		$catalog = $this->_xml->getElementsByTagName(XMLCat::CAT_NODE)->item(0);
		$imageTag = $this->_xml->createElement(XMLCat::IMAGE_NODE);
		
		if($nbDates==0){				
			$catalog->appendChild($imageTag);
			return $imageTag;
		}
		
		$index = 0;

		foreach ($this->_dates as $key=>$value) {
			$this->_log->addlog("compare date ".date_format($value, IPTC::DATE_FORMAT)." with ".date_format($date, IPTC::DATE_FORMAT));
			if ($value>$date){
				break;
			}
			$index++;
		}
		$this->_log->addlog("index=".$index);
		$nodeAfter = $this->_xml->getElementsByTagName(XMLCat::IMAGE_NODE)->item($index);
	
		$catalog->insertBefore($imageTag, $nodeAfter);
		
		return $imageTag;
	}
	
	private function makeArrays(){
		
		$this->_dates = array();
		$this->_title = array();
		$this->_imgName = array();
		$this->_largeUrl = array();
		$this->_thumbUrl = array();
		//$this->_keywords; TODO voir ce cas particulier multidimensionnal array ?

		$catalog = $this->_xml->getElementsByTagName(XMLCat::CAT_NODE)->item(0);
		$imageList = $catalog->getElementsByTagName(XMLCat::IMAGE_NODE);
		
		$nbImg = $imageList->length;
		$this->_log->addlog($nbImg." xml images");
		
		if ($nbImg==0) {
			$this->_largeUrl[0] =  $_SERVER['DOCUMENT_ROOT']."/img/default.jpg";
			return;
		} 
		
		for ($i =0; $i< $nbImg; $i++) {
			$node = $imageList->item($i);
			$url = $node->getElementsByTagName(XMLCat::NAME_NODE)->item(0)->nodeValue;
			$this->_log->addlog("image".$i.":".$url);
			$date = $node->getElementsByTagName(XMLCat::DATE_NODE)->item(0)->nodeValue;
			$dateTime = DateTime::createFromFormat(IPTC::DATE_FORMAT,$date);
			$this->_dates[$url] = $dateTime;
			$this->_title[$url] =  $node->getElementsByTagName(XMLCat::TITLE_NODE)->item(0)->nodeValue;
			$this->_imgName[$i] = $url;
			$this->_largeUrl[$i] = $this->_config->large().$url;
			$this->_thumbUrl[$i] = $this->_config->thumbs().$url;
		}
	}
	
	public function getImgNames(){
		return $this->_imgName;
	}
	
	public function getLargeImages(){
		return $this->_largeUrl;
	}
	
	public function getThumbImages(){
		return $this->_thumbUrl;
	}
	
}

?>
