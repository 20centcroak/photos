<?php

/*BuildSite class builds the website with an empty copy.
*/
class BuildSite{
	
	public function __construct($url){
		//create XMLAccount file
		XMLAccount::createXMLAccount();
	}
	
}

?>