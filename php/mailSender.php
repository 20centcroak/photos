<?php

class MailSender{
	
	public static function activationMail($user){
		$txt_msg = "Welcome to Croak photos,\n
		Just one final step to validate your account, please click on the link below.\n 
		https://photos.croak.fr/activation.php?key=".urlencode($user->key())."\n
		---------------\n
		This is an automatic e-mail, please do not reply.";
		
		$hml_msg = "<h1>Welcome to Croak photos,</h1>
		<p>Just one final step to validate your account, please click on the link below. or copy-past in your web browser</p> 
		<p>https://photos.croak.fr/activation.php?key=".urlencode($user->key())."</p>
		<p>---------------</p>
		<p>This is an automatic e-mail, please do not reply.</p>";
		
		MailSender::sendTextMail($user->email(), "validate your account" , $txt_msg);
	}
	
	public static function recoveryMail($user){
		$txt_msg = "To change your password on Croak photos,\n
		please click on the link below.\n 
		https://photos.croak.fr/resetPassword.php?key=".urlencode($user->key())."\n
		---------------\n
		This is an automatic e-mail, please do not reply.";
		
		$hml_msg = "<p>To change your password on Croak photos,	please click on the link below.</p>
		<p>https://photos.croak.fr/resetPassword.php?key=".urlencode($user->key())."</p>
		<p>---------------</p>
		<p>This is an automatic e-mail, please do not reply.</p>";
		
		MailSender::sendTextMail($user->email(), "reset your password" , $txt_msg);
	}
	
	private static function sendMail($email, $subject, $txt_msg, $html_msg){
	
		$boundary = "-----=".md5(rand());
		
		$header = "From: \"Croak Photos\"<cgi-mailer@kundenserver.de> \n";
		$header.= "Reply-to: \"Croak Photos\"<support@croak.fr> \n";
		$header.= "MIME-Version: 1.0 \n";
		$header.= "X-Priority: 3 \n";
		$header.= "Content-Type: multipart/alternative; \n boundary=\"$boundary\" \n";
		
		$message = "\n--$boundary\n";
		$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\" \n";
		$message.= "Content-Transfer-Encoding: 8bit \n";

		$message.= "\n $txt_msg \n";
		
		$message.= "\n--$boundary\n";
		$message.= "Content-Type: text/html; charset=\"ISO-8859-1\" \n";
		$message.= "Content-Transfer-Encoding: 8bit \n";
		
		$message.= "\n $html_msg \n";
		
		$message.= "\n--$boundary--\n";
		$message.= "\n--$boundary--\n";

		
		mail($email,$subject,$message,$header);
	}
	
	private static function sendTextMail($email, $subject, $txt_msg){
	
		$boundary = "-----=".md5(rand());
		
		$header = "From: \"Croak Photos\"<cgi-mailer@kundenserver.de> \n";
		$header.= "Reply-to: \"Croak Photos\"<support@croak.fr> \n";
		$header.= "MIME-Version: 1.0 \n";
		$header.= "X-Priority: 3 \n";
		$header.= "Content-Type: multipart/alternative; \n boundary=\"$boundary\" \n";
		
		$message = "\n--$boundary\n";
		$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\" \n";
		$message.= "Content-Transfer-Encoding: 8bit \n";

		$message.= "\n $txt_msg \n";
		
		$message.= "\n--$boundary--\n";

		
		mail($email,$subject,$message,$header);
	}
	
}