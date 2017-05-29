<?php
session_start();
if (!isset($_SESSION["gallery"])){
	header("Location:https://photos.croak.fr");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Croak Photos!</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/thumbs.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="js/oXHR.js"></script>
  <script src="js/manageThumbs.js"></script> 
</head>

<body onload="request(readJson)">

<div class="container-fluid text-center">    
  <h1 id="title"></h1>
  <div  id="grid" class="row">
  </div>
</div>

</body>
</html>
