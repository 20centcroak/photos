<?php

include_once "manageLogs.php";
$log = new ManageLogs();
$log->dlog("\\\\\\\\\\\\\\\\\\\\NEW UPLOAD//////////////////");

session_start();
empty($_SESSION["user"])?throw new RuntimeException('no user set'):$userName=$_SESSION["user"];


$log->addlog(count($_FILES)." files to upload");



$dir = '../usr/vp/upload/';

foreach($_FILES as $file){
	$log->addlog($file['name']);
	$fileName = $file['name'];
	move_uploaded_file($file['tmp_name'], $dir.$fileName)?$log->addlog('sucessful'):$log->addlog('failed');
}




// Security tips you must know before use this function :

// First : make sure that the file is not empty.

// Second : make sure the file name in English characters, numbers and (_-.) symbols, For more protection.

// You can use below function as in example

// <?php

// /**
// * Check $_FILES[][name]
// *
// * @param (string) $filename - Uploaded file name.
// * @author Yousef Ismaeil Cliprz
// */
// function check_file_uploaded_name ($filename)
// {
    // (bool) ((preg_match("`^[-0-9A-Z_\.]+$`i",$filename)) ? true : false);
// }

// ?>

// Third : make sure that the file name not bigger than 250 characters.

// as in example :

// <?php

// /**
// * Check $_FILES[][name] length.
// *
// * @param (string) $filename - Uploaded file name.
// * @author Yousef Ismaeil Cliprz.
// */
// function check_file_uploaded_length ($filename)
// {
    // return (bool) ((mb_strlen($filename,"UTF-8") > 225) ? true : false);
// }

// ?>

// Fourth: Check File extensions and Mime Types that you want to allow in your project. You can use : pathinfo() http://php.net/pathinfo

// or you can use regular expression for check File extensions as in example

// #^(gif|jpg|jpeg|jpe|png)$#i

// or use in_array checking as

// <?php

// $ext_type = array('gif','jpg','jpe','jpeg','png');

// ?>

// You have multi choices to checking extensions and Mime types.

// Fifth: Check file size and make sure the limit of php.ini to upload files is what you want, You can start from http://www.php.net/manual/en/ini.core.php#ini.file-uploads

// And last but not least : Check the file content if have a bad codes or something like this function http://php.net/manual/en/function.file-get-contents.php.

// You can use .htaccess to stop working some scripts as in example php file in your upload path.

// use :

// AddHandler cgi-script .php .pl .jsp .asp .sh .cgi
// Options -ExecCGI 

// Do not forget this steps for your project protection.

?>