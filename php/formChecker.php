<?php
require_once 'php/XMLAccount.php';
require_once 'php/usr.php';
require_once 'accounts/empty.php';

class FormChecker{
	
	private $_xml;
	private $_users;
	
	//private constructor
	private function __construct($xml){
		$this->_xml = $xml;
		$this->_users = $this->_xml->getUsers();
	}
	
	//performs tests before creating the Object, gets xml catalog
	public static function FormCheckerFactory(){
		$xml = XMLAccount::xmlAccountFactory();
		if ($xml==null){
			session_start();
			$_SESSION["msg"]="Account creation has failed - please try again";
			return false;
		}		
		return new FormChecker($xml);
	}
	
	//checks if a new account can be created with given data
	public function signup($name, $email, $password){
		session_start();
			  
		if (!FormChecker::nameOK($name) || !FormChecker::emailOK($email) || !FormChecker::passOK($password)) {			
			return false;
		}
		
		$_SESSION["emailForm"] = $email;
		$_SESSION["nameForm"] = $name;
		
		if($this->userExists($name) || $this->userExists($email)){			
			$_SESSION["msg"]="this user name or email is already used";
			return false;
		}
		
		$options = Encryption::options();
		$hash = Encryption::encrypt($password, $name, $options);
		$key = md5($name.microtime(TRUE)*100000);
		$user = User::create($name, $hash, $email, $key);
		if(!$this->_xml->addUser($user)){
			$_SESSION["msg"]="Account creation failed - please try again";
			return false;
		}
		
		require 'php/mailSender.php';
		MailSender::activationMail($user);
		
		header("Location:https://photos.croak.fr/signupSuccess.php");	
	}
	
	//checks if the given user can connect with this password
	public function signin($name, $password){
		
		if( (!FormChecker::nameOK($name) && !FormChecker::emailOK($name)) || !FormChecker::passOK($password)){
			return false;
		}
		
		session_start();
		$user = $this->userExists($name);
		if($user==null){
			$_SESSION["msg"]="No such account name in our database. Please verify your name or mail address.";
			return false;
		}
			
		if(!$user->activated()){
			$_SESSION["msg"]="Your account has not been activated yet. Please check your email to finalize your inscription.";
			return false;
		}
		
		if(!Encryption::decrypt($password, $user->password())){			
			$_SESSION["msg"]="Wrong password";
			return false;
		}
		
		$_SESSION["user"] = $user->name();	
		
		$user->updateTime();
		$this->_xml->update($user);
		
		$_SESSION["nb_errors"] = 0;
		header("Location:https://photos.croak.fr/admin.php");
	
	}
	
	//checks if the update is correct
	public function update($name, $alias, $email){
		
		include_once "manageLogs.php";
		$log = new ManageLogs();

		if( !FormChecker::nameOK($name)  || !FormChecker::aliasOK($alias) || !FormChecker::emailOK($email)){
			return false;
		}

		session_start();
		$user = $this->userExists($name);
		if($user==null){
			$_SESSION["msg"]="No such account name in our database. Please verify your name or mail address.";
			return false;
		}
		
		if($this->mailExistsElseWhere($name, $email)){
			$_SESSION["msg"]="this e-mail address is already registered";
			return false;
		}

		$user->update($alias, null, null, $email, null);
		$this->_xml->update($user);

		return true;
	}
	
	//checks user and password validity and updates password
	public function resetPassword($name, $password){
		
		if (!FormChecker::nameOK($name) || !FormChecker::passOK($password)) {
			return false;
		}
		
		$options = Encryption::options();
		$hash = Encryption::encrypt($password, $name, $options);
		
		session_start();
		$user = $this->userExists($name);
		if($user==null){
			$_SESSION["msg"]="No such account name in our database. Please verify your name.";
			return false;
		}
		$_SESSION["emailForm"] = $user->email();
		
		$user->update($user->alias(), $hash, $user->title(), $user->email(), true);
		$this->_xml->update($user);

		$_SESSION["msg"]="Your password has been sucessfully updated";
		$_SESSION["user"] = $user->name();
		header("Location:https://photos.croak.fr/admin.php");
		
		return true;		
	}
	
	//checks e-mail adress and then desactivates account and send a link by e-mail to reset password and reactivate account
	public function passRecovery($email){
		if(!FormChecker::emailOK($email)){
			return false;
		}
		
		$user = $this->userExists($email);
		if($user==null){
			$_SESSION["msg"]="No such account name in our database. Please verify your name or mail address.";
			return false;
		}
		$_SESSION["nameForm"] = $user->name();
		if(!$user->activated()){
			$_SESSION["msg"]="Your account has not been activated yet. Please check your email to finalize your inscription.";
			return false;
		}
		
		$user->update($user->alias(), $user->password(), $user->title(), $user->email(), false);
		$this->_xml->update($user);
		
		require 'php/mailSender.php';
		MailSender::recoveryMail($user);

		header("Location:https://photos.croak.fr/passRecoverySuccess.php");
	}
	
	//activates account if the key is correct
	public function activation($user){
		if(!$user){
			return false;
		}
		$user->activate();
		$this->_xml->update($user);
		session_start();
		$_SESSION["msg"]="Your account is now available";	
		header("Location:https://photos.croak.fr/signin.php");
	}
	
	//checks compliance with name format
	public static function nameOK($name){
		session_start();
		if( (preg_match("/^[a-zA-Z0-9]+[a-zA-Z0-9_-]+$/",$name) && strlen($name)>=2 && strlen($name)<20) ){
			$_SESSION["nameForm"] = $name;
			return true;
		}
		else{
			$_SESSION["msg"]="User name must contain at least 2 characters and less than 20 characters. The only allowed special characters are - and _";
			return false;
		}
	}
	
	//checks compliance with email format
	private static function emailOK($email){
		session_start();
		if (preg_match("/^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/",$email)){
			$_SESSION["emailForm"] = $email;
			return true;
		}
		else{
			$_SESSION["msg"]="Enter a valid e-mail address";
			return false;
		}
	}
	
	//checks compliance with name format
	public static function titleOK($title){
		session_start();
		if( preg_match("/^[\wàáâãäåçèéêëìíîïðòóôõöùúûüýÿ@!$µ*-+?.:;~'#`=]{1}[\w àáâãäåçèéêëìíîïðòóôõöùúûüýÿ@!$µ*-+?.:;~'#`=]{0,29}$/",$title)){
			$_SESSION["titleForm"] = $title;
			return true;
		}
		else{
			$_SESSION["msg"]="title should be 1 to 30-character long without special characters and not begining with white space";
			return false;
		}
	}
	
	//checks compliance with name format
	public static function aliasOK($alias){
		session_start();
		if( preg_match("/^[\wàáâãäåçèéêëìíîïðòóôõöùúûüýÿ@!$µ*-+?.:;~'#`=]{1}[\w àáâãäåçèéêëìíîïðòóôõöùúûüýÿ@!$µ*-+?.:;~'#`=]{0,29}$/",$alias) ){
			$_SESSION["aliasForm"] = $alias;
			return true;
		}
		else{
			$_SESSION["msg"]="alias should be 1 to 30-character long without special characters and not begining with white space";
			return false;
		}
	}
	
	//checks compliance with password format
	private static function passOK($password){
		if (strlen($password)>=6) {
			return true;
		}
		else{
			session_start();
			$_SESSION["msg"]="Password should be at least 6-character long";
		}
	}
	
	//verify user existence in database
	private function userExists($name){
		foreach($this->_users as $user){
			if($user->name() == $name || $user->email() == $name){
				return $user;
			}
		}
		return false;
	}
	
	//verify mail existence in database
	private function mailExistsElseWhere($name, $email){
		foreach($this->_users as $user){
			if($user->email() === $email && $name!=$user->name()){
				return true;
			}
		}
		return false;
	}

	//formats input 
	public static function format_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}
	
	//from a key, look for the corresponding user, checks if account is already validated
	//and send user if not
	public static function validate($key){
		session_start();
		$xml = XMLAccount::xmlAccountFactory();
		$users = $xml->getUsers();
		
		foreach($users as $user){
			
			$keydb = $user->key();
			if($key===$keydb){
				
				$_SESSION["nameForm"] = $user->name();
				
				if (strtotime('+7 days', $user->lastUpdate()) < strtotime('now')){			
				$_SESSION["msg"]="The password recovery link is out of date";
				return null;
				}
			
				if($user->activated()){
					$_SESSION["msg"]="Your account has already been validated";
					return null;
				}	
				
				return $user;
			}	
		}
		$_SESSION["msg"]="No corresponding account";
		return null;
	}
	
	
}