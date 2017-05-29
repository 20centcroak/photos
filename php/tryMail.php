<?php

	$header = "From: \"Croak Photos\"<cgi-mailer@kundenserver.de> \n";
		$header.= "Reply-to: \"Croak Photos\"<support@croak.fr> \n";
		$header.= "MIME-Version: 1.0 \n";
		$header.= "X-Priority: 3 \n";
		$header.= "Content-Type: multipart/alternative; \n boundary=\"$boundary\" \n";
				
		$message.= "\n--$boundary\n";
		$message.= "Content-Type: text/html; charset=\"ISO-8859-1\" \n";
		$message.= "Content-Transfer-Encoding: 8bit \n";
		$message.= "\n ";
		$message.= file_get_contents("activation_mail.html");
		$message.= " \n";
		
		$message.= "\n--$boundary--\n";
		
		mail("vpaveau@yahoo.fr","essai mail html",$message,$header);
		
?>