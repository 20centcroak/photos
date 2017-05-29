<?php

class ManageLogs{
	
	private $_write;
	private $_folder;
		
	public function __construct($user){
		$this->_folder = $_SERVER['DOCUMENT_ROOT']."/";
		if($user!=null){
			$dir = $user->getDirectory();
			$configFile = $user->config();
			$json = file_get_contents($this->_folder.$dir.$configFile);	
			$obj = json_decode($json, true);
			$verbose = $obj['verbose'];	
			$this->_write = $verbose==="true";
		}
		else {
			$this->_write = true;
		}
	}
	
	public function dlog($msg){
		
		if(!$this->_write){
			return;
		}
		
		$log = fopen($this->_folder."php/logs.txt","a") or die("Unable to open file!");
		$stampDate=date("j M Y \- H \h i \m s \s");
		fwrite($log, $stampDate."\n");
		fwrite($log,$msg."\n");
		fclose($log);
	}
	
	public function addLog($msg){

		if(!$this->_write){
			return;
		}
		$log = fopen($this->_folder."php/logs.txt","a") or die("Unable to open file!");
		fwrite($log,$msg."\n");
		fclose($log);
	}
}
?>