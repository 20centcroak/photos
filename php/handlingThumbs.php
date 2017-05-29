<?php

header("Content-Type: text/plain"); 
session_start();
$userName = $_SESSION["gallery"];

include_once "UserDoor.php";
$userDoor = UserDoor::userDoorFactory($userName);
if($userDoor==null){
	$_SESSION["msg"]="a problem occured with this account";
	header("Location:https://photos.croak.fr");
}
$catalog = $userDoor->getCatalog();
if ($catalog==null){
	echo("catalog unavailable");
	return;
}

echo(addTocols($catalog->getThumbImages(),$catalog->getImgNames(), $userDoor));


function addToCols($thumbFiles, $names, $userDoor) {
	$json = array();
	$json["title"] = $_SESSION["title"];;
	
	$nbCols = $userDoor->getConfig()->nbCols();
	for ($i=0; $i<$nbCols; $i++){
		$json["col"][$i]=array();
	}
	$indexCol = 0;
	foreach($thumbFiles as $key=>$thumb){
		if($indexCol==$nbCols){
			$indexCol=0;
		}
		$link = '<a href=diplay.php?img='.$key.'><img src="'.$thumb.'" style="width:100%"></a>';
		array_push($json["col"][$indexCol],$link) ;
		$indexCol++;
	}
	return json_encode($json);
}

?>
