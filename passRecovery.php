<?php
	require 'php/formChecker.php';
	session_start();
	$_SESSION["nb_errors"]=0;
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
		if (!empty($_POST["smartControlForm"])){
			header("Location:https://photos.croak.fr");
		}

		$email = FormChecker::format_input($_POST["inputEmail"]);
		$checker = FormChecker::FormCheckerFactory();
		
		if($checker){
			$checker->passRecovery($email);		
		}
		else {
			$_SESSION["msg"]="Account creation failed - please try again";
		}
	}		

?>

<?php	require('header.htm'); ?>

  <body class="bgimg">

    <div class="container">
		<?php include_once 'php/modalMsg.php' ;	?>	
		<form class="form-signin" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >
			<h3 class="form-signin-heading">Forgot your password?</h3>
			<label for="inputEmail" class="sr-only">e-mail</label>
			<input type="email" id="inputEmail" name="inputEmail" class="form-control" placeholder="E-mail" 
			<?php 
					$emailForm = $_SESSION["emailForm"];
					echo $s=(empty($emailForm)) ? "":"value=\"$emailForm\"";
				?>
			required autofocus>
			<label class="sr-only control">spam control below - please leave it blank</label>
			<input name="smartControlForm" type="text" id="smartControlForm" class="form-control control" placeholder="spam control - please leave it blank" />
			<button class="btn btn-lg btn-primary btn-block" type="submit">Send link</button>
			<a href="signin.php">back to identification</a>
		</form>
    </div>
	<script>
		<?php
			if($msg){echo"$('#myModal').modal('show');";}
			else {echo"$('#myModal').modal('hide');";}
		?>
	</script>
  </body>
</html>
