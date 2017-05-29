<?php
	
	require_once 'php/formChecker.php';
	session_start();
	
	if($_SESSION["nb_errors"]>3){
		header("Location:https://photos.croak.fr/passRecovery.php");
	}
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
		if (!empty($_POST["smartControlForm"])){
			header("Location:https://photos.croak.fr");
		}
			
		$name = FormChecker::format_input($_POST["inputName"]);
		$password= FormChecker::format_input($_POST["inputPassword"]);
		$checker = FormChecker::FormCheckerFactory();
		
		if($checker){
			if (!$checker->signin($name, $password)){
				$_SESSION["nb_errors"]++;
			}		
		}
		else{
			$_SESSION["msg"]="Authentication failed - please try again";
		}

	}
?>

<?php	require('header.htm'); ?>

  <body class="bgimg">
    <div class="container">	
		<?php include_once 'php/modalMsg.php' ;	?>	
		<form class="form-signin" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >
			<h2 class="form-signin-heading">Sign in</h2>
			<div class="input-group">
				<label for="inputName" class="sr-only">User name or e-mail</label>
				<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
				<input type="text" id="inputName" name="inputName" class="form-control" placeholder="User name or e-mail" 
					<?php 
						$nameForm = $_SESSION["nameForm"];
						echo $s=(empty($nameForm)) ? "":"value=\"$nameForm\""; 
					?> required autofocus />
			</div>
			<div class="input-group">
				<label for="inputPassword" class="sr-only">Password</label>
				<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
				<input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required />
			</div>
			<label class="sr-only control">spam control below - please leave it blank</label>
			<input name="smartControlForm" type="text" id="smartControlForm" class="form-control control" placeholder="spam control - please leave it blank" />
			<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
			<p>&nbsp</p>
			<p><a href="passRecovery.php">forgot your password?</a></p>
			<p><a href="signup.php">not registered yet?</a></p>
		</form>
    </div>
    <script>
			<?php echo "var msg=\"$msg\";";?>
			msg ? $('#myModal').modal('show'):$('#myModal').modal('hide');
		</script>
  </body>
</html>
