<?php
	session_start();
	$_SESSION["image"] = $_GET["img"];
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>20cent's</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="robots" content="noindex, nofollow">
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"/>
	<link rel="icon" href="/favicon.ico" type="image/x-icon"/>
	<link rel="stylesheet" href="css/w3.css">
	<link rel="stylesheet" href="css/display.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
	<!-- IE6-8 support of HTML5 elements --> <!--[if lt IE 9]>
	<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<body onload="request(displayFirst,0)" onresize="resize()" class="w3-black">

<div class="w3-display-container">
	<img id="image">
	<a id="left" onclick="clickLeft()" class="w3-btn-floating w3-display-left" >&#10094;</a>
    <a id="right" onclick="clickRight()" class="w3-btn-floating w3-display-right" >&#10095;</a>
</div>  


<script src="js/oXHR.js"></script>
<script src="js/manageDisplay.js"></script> 

</body>
</html>