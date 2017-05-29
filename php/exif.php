<?php

	include_once  'manageLogs.php';

	function getExif($url){
		
		dlog("get Exif data from ".$url);
		
		$exif = exif_read_data($url, 'IFD0');
		
		if($exif===false){
			addLog( "No header data found");
			return;
		} 

		foreach ($exif as $key => $section) {
			foreach ($section as $name => $val) {
				addLog("$key.$name: $val");
			}
		}
	}
?>