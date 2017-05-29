<?php
	require_once 'php/UserDoor.php';
	session_start();
	$name = $_SESSION["user"];
	$msg = $_SESSION["msg"];
	$door = UserDoor::userDoorFactory($name);
	if ($door==null) header("Location:https://photos.croak.fr/signin.php");
	$user = $door->getUser();
	$title = $user->title();
	$config = $door->getConfig();
	$bck = $config->background();
	$nbCols = $config->nbCols();
?>
<!doctype html>
<html lang="en">
  <head>
  	<meta charset="utf-8" />
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
  	<link rel="icon" href="favicon.ico">
  	<title>Croak Photos! administration</title>
  	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
  	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="https://getbootstrap.com/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto:100,600|Material+Icons' rel='stylesheet' type='text/css'>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="css/spectrum.css" rel="stylesheet">
    <link href="css/dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="css/thumbs.css">
  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <span class="navbar-brand">Administration</span>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="#" onclick="loadUser(this)"><i class="material-icons">person</i></a></li>
            <li><a href="#">Help</a></li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container-fluid">
      <?php include_once 'php/modalMsg.php' ;	?>
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li><a href="#" onclick="croakAdmin.loadUser(this)"><i class="material-icons"  aria-hidden="true">person</i><p>Profile</p></a></li>
            <li><a href="#" onclick='<?php echo"croakAdmin.loadGallery(\"$title\", \"$bck\", $nbCols, this)"; ?>'><i class="material-icons"  aria-hidden="true">palette</i><p>gallery settings</p></a></li>
            <li><a href="#">Export</a></li>
          </ul>
        </div>
        <div id="main" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        </div>
      </div>
    </div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="js/spectrum.js"></script>
  <script src="js/manageThumbs.js"></script>
  <script src="js/oXHR.js"></script>
  <script src="js/admin.js"></script>
	<script>
	  <?php echo"var msg=\"$msg\";";?>
		(msg)?$('#myModal').modal('show'):$('#myModal').modal('hide');
	</script>
</body>
</html>