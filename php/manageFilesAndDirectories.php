<?php

/*ManageFilesAndDirectories class proposes convenient static methods to work with files and directories
*/
class ManageFilesAndDirectories{
	
	//list jpg files in $dir
	public static function getImgFiles($dir){
		$openDir = openDir($dir);
		$imgFiles = array();		
		while($entry = readdir($openDir)){
			if (eregi(".jpg",$entry) || eregi(".jpeg",$entry)) {
				$imgFiles[]=$dir."/".$entry;
				//array_push($imgFiles,$dir."/".$entry);
			}
		}
		closeDir($openDir);
		return $imgFiles;
	}
	
	//list all directories present in a parent directory
	public static function listDir($dir){
		$files = scandir($dir);
		
		$dirs = array();
		foreach($files as $file){
			if(is_dir($file)){
				$dirs[]=$file;
			}
		}
		
		return $dirs;
	}
	
	//replace all occurences of $target by $replacement in the file found at url $filename
	public static function replaceLineInFile($filename, $target, $replacement){
		return ManageFilesAndDirectories::copyReplaceLineInFile($filename, $filename, $target, $replacement);
	}
	
	//copy and replace all occurences of $target by $replacement in the file found at url $filename to the file found at url $copyfile
	public static function copyReplaceLineInFile($filename, $copyfile, $target, $replacement){
		
		include_once "manageLogs.php";
		$log = new ManageLogs();
		$log->dLog("copyReplaceLineInFile");
		$log->dLog("target=".$target);
		$log->dLog("replacement=".$replacement);
		
		$trimmed = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$file = fopen($copyfile, "w");
		if($file===false){
			return false;
		}
		foreach($trimmed as $line){
			$newLine = str_replace($target, $replacement, $line)."\n";
			if(!fwrite($file, $newLine)){
				return false;
			}
		}

		return fclose($file);
	}
	
}
?>