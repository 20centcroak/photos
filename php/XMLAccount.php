<?php
include_once "manageLogs.php";
require_once "usr.php";
include_once "XMLCat.php";
include_once "manageFilesAndDirectories.php";

/*XMLAccount class manages the accounts.xml file which should be present at the url defined by the const field $_location.
*account.xml file is a descriptive file referencing all users of the website. It is a xml database of users.
*It is possible to create a new accounts.xml file by calling the static method createXMLAccount. A backup of the current account.xml is then created. 
*Warning : the new account.xml file will be empty and all references to the user accounts wil be lost (but the accounts themselves will not be deleted)
*There is no public constructor of class XMLAccount. It is possible to get a XMLAccount object by calling xmlAccountFactory which verifies that the account.xml file can be loaded.
*To add a new user to the database, just call the addUser method.
*/
class XMLAccount{
	
	const ACCOUNT_NODE="account";
	const USER_NODE="user";
	const ALIAS_NODE="alias";
	const PASS_NODE="password";
	const TITLE_NODE="title";
	const NAME_NODE="name";
	const MAIL_NODE="email";
	const CONFIG_NODE="config";
	const CREATED_NODE="created";
	const LAST_UPDATE_NODE="lastUpdate";
	const KEY_NODE="key";
	const ACTIVATED_NODE="activated";
	
	private $_location;	
	private $_log;
	private $_xml;
	private $_catUrl;
	private $_users;

	private function __construct($xml){
		
		$this->_location = $_SERVER['DOCUMENT_ROOT']."/accounts/accounts.xml";
		$this->_log = new ManageLogs();	
		$this->_xml=$xml;
		$this->makeArrays();	
	}
	
	//gets an XMLAccount object if the accounts.xml file can be loaded
	public static function xmlAccountFactory(){
		
		$location = $_SERVER['DOCUMENT_ROOT']."/accounts/accounts.xml";
		
		$log = new ManageLogs();
		$log->dlog("xmlAccountFactory");
		$url =$location;
		
		$xml = new DOMDocument("1.0", "utf-8");
		$xml->preserveWhiteSpace = false;
		$xml->formatOutput = true;

		if (!file_exists($url)){
			$log->addlog("file does not exist");
			return null;
		}
		
		if(!$xml->load($url)) {
			$log->addlog("account catalog can't be loaded");			
			return null;
		}

		return new XMLAccount($xml);
	}
	
	//creates an empty accounts.xml file and backup existing files
	public static function createXMLAccount(){
	
		$log = new ManageLogs();
		$location = $_SERVER['DOCUMENT_ROOT']."/accounts/accounts.xml";
	
		if (file_exists($location)){
			$log->addlog("backup of existing xml account file");
			$date = new DateTime();
			$timeStamp = date_timestamp_get($date);
			if (rename($location, $location.$timeStamp)){
				$log->addlog("backup of xml account file successfull");
			}
			else {
				$log->addlog("backup of xml account file failed");
				return null;
			}	
		}
	
		$xml = new DOMDocument("1.0", "utf-8");
		$xml->preserveWhiteSpace = false;
		$xml->formatOutput = true;
		$catTag = $xml->createElement(XMLAccount::ACCOUNT_NODE);
		$xml->appendChild($catTag);
		$xml->save($location);	
	}
		
	// adds a new user to the catalog
	public function addUser($user){
		
		$this->_log->addlog("add user");
		
		$this->createNode($user);
		$defaultConfig = $_SERVER['DOCUMENT_ROOT']."/build/config.json";
		$defaultIndex = $_SERVER['DOCUMENT_ROOT']."/build/index.php.txt";
		$targetPath = $user->getAbsoluteDirectory();
		$this->_log->addlog("targetPath = ".$targetPath);
		$usrConfig = $targetPath."/".$user->config();
		$usrIndex = $targetPath."/index.php";
		
		if (!mkdir($targetPath)){
			$this->_log->addlog("main directory creation failed");
			return false;
		}
		
		if (!copy($defaultConfig, $usrConfig)){
			$this->_log->addlog("copy of config file ".$defaultConfig." to ".$usrConfig." failed");
			return false;
		}
		
		if (!ManageFilesAndDirectories::copyReplaceLineInFile($defaultIndex, $usrIndex,'*$userName*','$userName="'.$user->name().'";')){
			$this->_log->addlog("copy of index ".$defaultIndex." to ".$usrIndex." failed");
			return false;
		}
		
		$json = file_get_contents($usrConfig);	
		$config = json_decode($json, true);
		
		$dir = $config["thumbs"];
		if(!mkdir($targetPath.$dir)){
			$this->_log->addlog("creation of thumbs dir failed");
			return false;
		}
		
		$dir = $config["large"];
		if(!mkdir($targetPath.$dir)){
			$this->_log->addlog("creation of large dir failed");
			return false;
		}
		
		$dir = $config["fullsize"];
		if(!mkdir($targetPath.$dir)){
			$this->_log->addlog("creation of fullsize dir failed");
			return false;
		}
		
		$dir = $config["upload"];
		if(!mkdir($targetPath.$dir)){
			$this->_log->addlog("creation of upload dir failed");
			return false;
		}
		
		$catalog = $targetPath."catalog.xml";
		XMLCat::saveXMLCat(XMLCat::createXMLCat(), $catalog);
		
		if(!$this->save()){			
			return false;
		}
		$this->_log->addlog("new user created sucessfully");
		$this->makeArrays();
		return true;
	}
	
	//gets a User object according to the user name, or null if the user name is not found in the catalog
	public function getUser($userName){
		foreach($this->_users as $user){
			if(strcmp($user->name(), $userName)==0){
				return $user;
			}
		}
		return null;
	}
	
	//returns the array of all users referenced in the database
	public function getUsers(){
		return $this->_users;
	}
	
	//updates the user data in xml file
	public function update($user){		

		//update node XMLAccount::PASS_NODE
		$nodes = $this->getNodes($user, XMLAccount::PASS_NODE);
		$nodes->item(0)->nodeValue=$user->password();
		
		//update node XMLAccount::ALIAS_NODE
		$nodes = $this->getNodes($user, XMLAccount::ALIAS_NODE);
		$nodes->item(0)->nodeValue=$user->alias();
		
		//update node XMLAccount::EMAIL_NODE
		$nodes = $this->getNodes($user, XMLAccount::MAIL_NODE);
		$nodes->item(0)->nodeValue=$user->email();
		
		//update node XMLAccount::TITLE_NODE
		$nodes = $this->getNodes($user, XMLAccount::TITLE_NODE);
		$nodes->item(0)->nodeValue=$user->title();
		
		//update node XMLAccount::LAST_UPDATE_NODE
		$nodes = $this->getNodes($user, XMLAccount::LAST_UPDATE_NODE);
		$nodes->item(0)->nodeValue=$user->lastUpdate();

		//update node XMLAccount::ACTIVATED_NODE
		$nodes = $this->getNodes($user, XMLAccount::ACTIVATED_NODE);
		$nodes->item(0)->nodeValue=$user->activated();		
		
		$this->save();
	}
	
	//saves the accounts.xml file
	private function save(){
		$this->_log->dlog("save catalog at address ".$this->_location);
		return $this->_xml->save($this->_location);	
	}

	//create a new user in the xml hierarchy
	private function createNode($user){
	
		$catalog = $this->_xml->getElementsByTagName(XMLAccount::ACCOUNT_NODE)->item(0);
		$userTag = $this->_xml->createElement(XMLAccount::USER_NODE);
		$catalog->appendChild($userTag);
		
		$parent = $userTag;
		//création du noeud XMLAccount::NAME_NODE
		$tag = $this->_xml->createElement(XMLAccount::NAME_NODE);
		$parent->appendChild($tag);
		$property = $user->name();
		$propertyText = $this->_xml->createTextNode($property);
		$tag->appendChild($propertyText);
		
		//création du noeud XMLAccount::ALIAS_NODE
		$tag = $this->_xml->createElement(XMLAccount::ALIAS_NODE);
		$parent->appendChild($tag);
		$property = $user->alias();
		$propertyText = $this->_xml->createTextNode($property);
		$tag->appendChild($propertyText);
		
		//création du noeud XMLAccount::PASS_NODE
		$tag = $this->_xml->createElement(XMLAccount::PASS_NODE);
		$parent->appendChild($tag);
		$property = $user->password();
		$propertyText = $this->_xml->createTextNode($property);
		$tag->appendChild($propertyText);
		
		//création du noeud XMLAccount::MAIL_NODE
		$tag = $this->_xml->createElement(XMLAccount::MAIL_NODE);
		$parent->appendChild($tag);
		$property = $user->email();
		$propertyText = $this->_xml->createTextNode($property);
		$tag->appendChild($propertyText);
		
		//création du noeud XMLAccount::TITLE_NODE
		$tag = $this->_xml->createElement(XMLAccount::TITLE_NODE);
		$parent->appendChild($tag);
		$property = $user->title();
		$propertyText = $this->_xml->createTextNode($property);
		$tag->appendChild($propertyText);
				
		//création du noeud XMLAccount::CONFIG_NODE
		$tag = $this->_xml->createElement(XMLAccount::CONFIG_NODE);
		$parent->appendChild($tag);
		$property = $user->config();
		$propertyText = $this->_xml->createTextNode($property);
		$tag->appendChild($propertyText);
		
		//création du noeud XMLAccount::CREATED_NODE
		$tag = $this->_xml->createElement(XMLAccount::CREATED_NODE);
		$parent->appendChild($tag);
		$property = $user->creationDate();
		$propertyText = $this->_xml->createTextNode($property);
		$tag->appendChild($propertyText);
		
		//création du noeud XMLAccount::LAST_UPDATE_NODE
		$tag = $this->_xml->createElement(XMLAccount::LAST_UPDATE_NODE);
		$parent->appendChild($tag);
		$property = $user->lastUpdate();
		$propertyText = $this->_xml->createTextNode($property);
		$tag->appendChild($propertyText);
		
		//création du noeud XMLAccount::KEY_NODE
		$tag = $this->_xml->createElement(XMLAccount::KEY_NODE);
		$parent->appendChild($tag);
		$property = $user->key();
		$propertyText = $this->_xml->createTextNode($property);
		$tag->appendChild($propertyText);
		
		//création du noeud XMLAccount::ACTIVATED_NODE
		$tag = $this->_xml->createElement(XMLAccount::ACTIVATED_NODE);
		$parent->appendChild($tag);
		$property = $user->activated();
		$propertyText = $this->_xml->createTextNode($property);
		$tag->appendChild($propertyText);
		
	}
	
	private function getNodes($user, $tagName){
		$catalog = $this->_xml->getElementsByTagName(XMLAccount::ACCOUNT_NODE)->item(0);
		$userList = $catalog->getElementsByTagName(XMLAccount::USER_NODE);
		foreach($userList as $node){
			$nameItem = $node->getElementsByTagName(XMLAccount::NAME_NODE)->item(0)->nodeValue;	
			if($user->name()===$nameItem){
				return $node->getElementsByTagName($tagName);
			}
		}		
	}
	
	//get information about the user and create a User object with them
	private function makeArrays(){
		
		$this->_users = array();

		$catalog = $this->_xml->getElementsByTagName(XMLAccount::ACCOUNT_NODE)->item(0);
		$userList = $catalog->getElementsByTagName(XMLAccount::USER_NODE);		
		$nbUsers = $userList->length;
		$this->_log->addlog($nbUsers." xml images");
				
		for ($i =0; $i< $nbUsers; $i++) {
			$node = $userList->item($i);
			$name= $node->getElementsByTagName(XMLAccount::NAME_NODE)->item(0)->nodeValue;
			$alias= $node->getElementsByTagName(XMLAccount::ALIAS_NODE)->item(0)->nodeValue;
			$password= $node->getElementsByTagName(XMLAccount::PASS_NODE)->item(0)->nodeValue;
			$mail= $node->getElementsByTagName(XMLAccount::MAIL_NODE)->item(0)->nodeValue;
			$title= $node->getElementsByTagName(XMLAccount::TITLE_NODE)->item(0)->nodeValue;
			$config = $node->getElementsByTagName(XMLAccount::CONFIG_NODE)->item(0)->nodeValue;
			$created = $node->getElementsByTagName(XMLAccount::CREATED_NODE)->item(0)->nodeValue;
			$lastUpdate = $node->getElementsByTagName(XMLAccount::LAST_UPDATE_NODE)->item(0)->nodeValue;
			$key = $node->getElementsByTagName(XMLAccount::KEY_NODE)->item(0)->nodeValue;
			$activated = $node->getElementsByTagName(XMLAccount::ACTIVATED_NODE)->item(0)->nodeValue;
			
			$this->_users[$i] = new User($name, $alias, $password, $title, $mail, $config, $created, $lastUpdate, $key, $activated);
			($this->_users[$i]==null)?$this->_log->addlog("user null"):$this->_log->addlog("user formed");
		}
	}
	
}

?>
