<?php

header("Content-Type: text/plain");

$index = $_POST["ind"];

session_start();
$userName = $_SESSION["gallery"];
$imgNb = $_SESSION["image"]+$index;
$_SESSION["image"] = $imgNb;

include_once "UserDoor.php";
$userDoor = UserDoor::userDoorFactory($userName);
if($userDoor==null){
	$_SESSION["msg"]="a problem occured with this account";
	header("Location:https://photos.croak.fr");
}
$catalog = $userDoor->getCatalog();
$imagesUrl = $catalog->getLargeImages();

echo(getJSONLinks($imagesUrl, $imgNb));

function getJSONLinks($imagesUrl, $imgNb){

	$text = array();
	
	if ($imgNb>0){
		
		$index = $_POST["ind"];
		
		$text["left"] = $imagesUrl[$imgNb-1];
		list($width, $height) = getimagesize("../".$imagesUrl[$imgNb-1]);
		$text["w_left"] = $width;
		$text["h_left"] = $height;

	}
	
	$text["current"] = $imagesUrl[$imgNb];
	list($width, $height) = getimagesize("../".$imagesUrl[$imgNb]);
	$text["w"] = $width;
	$text["h"] = $height;
	
	if ($imgNb<count($imagesUrl)-1){
		$text["right"] = $imagesUrl[$imgNb+1];
		list($width, $height) = getimagesize("../".$imagesUrl[$imgNb+1]);
		$text["w_right"] = $width;
		$text["h_right"] = $height;
	}
	
	return json_encode($text);
}
?>
