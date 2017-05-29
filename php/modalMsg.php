<?php

	header("Content-type:text/html");

	session_start();
	$msg = "an error has occured";
	$msg = $_SESSION["msg"];
	$_SESSION["msg"] ="";

	$html = '
	<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog"  role="document">
			<div class="modal-content">
				<div class="modal-body">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<p>'.$msg.'</p>        
				</div>
			</div>
		</div>
	</div>
	';
	
	echo "$html";
?>