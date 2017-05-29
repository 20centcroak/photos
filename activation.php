<?php
 
require_once('php/XMLAccount.php'); 
require_once('php/formChecker.php');
$key = $_GET['key'];
$user = FormChecker::validate($key);

session_start();
if($user){
	$checker = FormChecker::FormCheckerFactory();		
	if($checker){
		$checker->activation($user);	
	}
	else {
		$_SESSION["msg"]="Account validation failed - please refresh";
	}
}

header("Location:https://photos.croak.fr/signin.php");
?>

 