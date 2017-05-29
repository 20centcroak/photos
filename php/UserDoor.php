<?php

include_once "XMLAccount.php";
include_once "XMLCat.php";
include_once "config.php"; 

//gets account settings of current user define by its name $name
class UserDoor{
	
	private $_config;
	private $_user;
	
	//private constructor, should be accessed by userDoorFactory
	private function __construct($user, $config){		
		$log = new ManageLogs();
		$userName = $user->name();
		$log->dlog("UserDoor for ".$userName);
		
		$this->_user = $user;
		$alias = $user->alias();
		$log->dlog("alias ".$alias);
		
		$this->_config = $config;
		$this->defineGlobals();
	}
	
	//checks id everything is ok before creating object
	public static function userDoorFactory($userName){
		$accounts = XMLAccount::xmlAccountFactory();
		if($accounts==null)	return null;
		$user = $accounts->getUser($userName);
		if($user==null) return null;
		
		$folder = $user->getAbsoluteDirectory();
		if(!file_exists($folder)) return null;
		//gets config file of current user
		$configName = $user->config();
		$config = new Config($folder, $configName);
		if($config==null)return null;
		return new UserDoor($user, $config);
	}
	
	//defines session variables
	private function defineGlobals(){
		session_start();
		$_SESSION["gallery"] = $this->_user->name();
		$_SESSION["title"] = $this->_user->title();
	}
	
	//user getter
	public function getUser(){
		return $this->_user;
	}
	
	//config getter
	public function getConfig(){
		return $this->_config;
	}

	//image catalog getter
	public function getCatalog(){
		//gets user catalog
		return XMLCat::xmlCatFactory($this->_config);
	}
}

?>