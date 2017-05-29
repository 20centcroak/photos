<?php

	require_once('php/formChecker.php');
	session_start();

	if ($_SERVER["REQUEST_METHOD"] == "POST") {		
	
		$name = FormChecker::format_input($_POST["inputName"]);
		$email = FormChecker::format_input($_POST["inputEmail"]);
		$password= FormChecker::format_input($_POST["inputPassword"]);
		$checker = FormChecker::FormCheckerFactory();
		
		if($checker){
			$checker->signup($name, $email, $password);		
		}
		else {
			$_SESSION["msg"]="Account creation failed - please try again";
		}
	}
	
	$msg = $_SESSION["msg"];
?>

<?php	require('header.htm'); ?>

  <body class="bgimg">

    <div class="container">
	
		<?php include_once 'php/modalMsg.php' ;	?>
	
		<form class="form-signin" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return verifSignUp(this)" >
			<h2 class="form-signin-heading">Create your account</h2>
			<div class="input-group">
				<label for="inputName" class="sr-only">User name</label>
				<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
				<input type="text" id="inputName" name="inputName" class="form-control" placeholder="User Name" 
				<?php 
					$nameForm = $_SESSION["nameForm"];
					echo $s=(empty($nameForm)) ? " ":"value=\"$nameForm\" "; 
				?>
				required autofocus >
			</div>
			<div class="input-group">
				<label for="inputEmail" class="sr-only">Email address</label>
				<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
				<input type="email" id="inputEmail" name="inputEmail" class="form-control" placeholder="Email address" 
				<?php 
					$emailForm = $_SESSION["emailForm"];
					echo $s=(empty($emailForm)) ? "":"value=\"$emailForm\"";
				?>
				required >
			</div>
			<div class="input-group">
				<label for="inputPassword" class="sr-only">Password</label>
				<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
				<input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required>
			</div>
			<div class="input-group pass-repeat">
				<label for="repeatPassword" class="sr-only">Repeat password</label>
				<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
				<input type="password" id="repeatPassword" class="form-control" placeholder="Repeat password" required>
			</div>
			<button class="btn btn-lg btn-primary btn-block" type="submit">Create account</button>
			<p>&nbsp</p>
			<p><a href="signin.php">Already registered?</a></p>
		</form>
    </div>
	<script src="js/sign.js"></script>
<script>
	<?php echo "var msg=\"$msg\";";?>
	msg ? $('#myModal').modal('show'):$('#myModal').modal('hide');
</script>
  
  </body>
</html>
