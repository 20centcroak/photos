<?php
	include_once "manageLogs.php";
	include_once "iptc.php";
	include_once "XMLCat.php";
	include_once "addUpload.php";
	include_once "XMLAccount.php";
	include_once "usr.php";
	include_once "buildSite.php";

	$log = new ManageLogs();
	$log->dlog("-----------STARTING----------");
	
	
	$userName = "vip";
	$folder = "usr/".$userName;

	
	//connect to the account database
	$xml = XMLAccount::xmlAccountFactory();
	if($xml==null){
		$log->dlog("user database is not available");
		return;
	}
	
	// $date = new DateTime();
	// $timeStamp = date_timestamp_get($date);
	// if ($xml->addUser(new User("vp", "", $folder, "config.json", $timeStamp, $timeStamp))){
		// $log->dlog("new user created sucessfully");
	// }
	// else{
		// $log->dlog("fail to create new user");
	// }
	
	// //create a new catalog
	// $xml = XMLCAT::createXMLCat();
	// $url="../".$folder."/catalog.xml";
	// XMLCAT::saveXMLCat($xml,$url);
	
	//manage uploads
	$log->addlog("connect to user ".$userName);
	$user = $xml->getUser($userName);
	if($user!=null){
		$log->addlog("connected");
		$upload = new AddUpload($user);
	}
	else {
		$log->dlog($userName.": no such user in database");
	}
	
	
	$log->dlog("----------everything has gone right!--------------");
?>