<?php

//User class defines et allow access (read only) to all data of a specific user. For example, its name, its directory, ...
class User{

	private $_name;
	private $_alias;
	private $_password;
	private $_title;
	private $_email;
	private $_config;
	private $_created;
	private $_lastUpdate;
	private $_key;
	private $_activated;
	
	
	//constructor : definition of all user fields 
	public function __construct($name, $alias, $password, $title, $email, $config, $created, $lastUpdate, $key, $activated){			
		//instanciate the global variables
		$this->_name=$name;
		$this->_alias=$alias;
		$this->_password=$password;
		$this->_title=$title;
		$this->_email=$email;
		$this->_config=$config;
		$this->_created=$created;
		$this->_lastUpdate=$lastUpdate;
		$this->_key=$key;
		$this->_gravatar=$gravatar;
		$this->_activated=$activated;		
	}
	
	//defines a new User just from its name and e-mail
	public static function create($name, $password, $email, $key){		
		$title = $name." photo gallery";
		$config = "config.json";
		$date = new DateTime();
		$timeStamp = date_timestamp_get($date);
					
		return new User($name, $name, $password, $title, $email, $config, $timeStamp, $timeStamp, $key, "0");
	}
	
	//update the last_update date
	public function updateTime(){
		$date = new DateTime();
		$this->_lastUpdate = date_timestamp_get($date);		
	}
	
	//activates user account
	public function activate(){
		$this->_activated="1";
	}

	//update the user parameters, name, creation date and key can't be updated
	public function update($alias=null, $password=null, $title=null, $email=null, $activated=null){
		if($alias!=null) $this->_alias=$alias;
		if($password!=null) $this->_password=$password;
		if($title!=null) $this->_title=$title;
		if($email!=null) $this->_email=$email;
		if($activated!=null) $this->_activated=$activated;
		$date = new DateTime();
		$this->_lastUpdate = date_timestamp_get($date);		
	}
	
	//get name associated with this USER
	public function name(){
		return $this->_name;
	}
	
	//get alias associated with this USER
	public function alias(){
		return $this->_alias;
	}
	
	//get password associated with this USER
	public function password(){
		return $this->_password;
	}
	
	//get title associated with this USER
	public function title(){
		return $this->_title;
	}
	
	//get email associated with this USER
	public function email(){
		return $this->_email;
	}
	
	//get activation key associated with this USER
	public function key(){
		return $this->_key;
	}
	
	//get activation status associated with this USER
	public function activated(){
		return $this->_activated;
	}
	
	//get absolute directory of user
	public function getAbsoluteDirectory(){
		return $_SERVER['DOCUMENT_ROOT']."/usr/".$this->_name."/";
	}
	
	//get relative directory of user
	public function getDirectory(){
		return "usr/".$this->_name."/";
	}
	
	//get config file name of user
	public function config(){
		return $this->_config;
	}	
	
	//get creation date of account
	public function creationDate(){
		return $this->_created;
	}
	
	//get last update date of account
	public function lastUpdate(){
		return $this->_lastUpdate;
	}
	
	//get gravatar
	public function gravatar(){
		return $this->get_gravatar($this->_email);
	}
	
	 /**
	 * Get either a Gravatar URL or complete image tag for a specified email address.
	 *
	 * @param string $email The email address
	 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
	 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
	 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
	 * @param boole $img True to return a complete IMG tag False for just the URL
	 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
	 * @return String containing either just a URL or a complete image tag
	 * @source https://gravatar.com/site/implement/images/php/
	 */
	function get_gravatar( $email, $s = 200, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
		$url = 'https://www.gravatar.com/avatar/';
		$url .= md5( strtolower( trim( $email ) ) );
		$url .= "?s=$s&d=$d&r=$r";
		if ( $img ) {
			$url = '<img src="' . $url . '"';
			foreach ( $atts as $key => $val )
				$url .= ' ' . $key . '="' . $val . '"';
			$url .= ' />';
		}
		return $url;
	}
	
}

?>