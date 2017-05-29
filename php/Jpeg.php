<?php

/*Jpeg class offers convenient static methods to process Jpeg files. No constructor object is public.
*all methods should be accessed statically
*/
class Jpeg{
	
	const QUALITY = 3;
	
	private function __construct(){
	}
	
	//resize images according to the targeted new width. Aspect ratio of the original image is preserved.
	//if the new width is greater than the original width, just copy the original file
	public static function resize_width($newWidth, $filename, $out){		
		list($width, $height) = getimagesize($filename);
		if ($width==0) {
			return false;
		}
		$percent = $newWidth/$width;

		return Jpeg::resize_pct($percent, $filename, $out);
	}
	
	//resize images according to the targeted new height. Aspect ratio of the original image is preserved.
	//if the new height is greater than the original height, just copy the original file
	public static function resize_height($newHeight, $filename, $out){		
		list($width, $height) = getimagesize($filename);
		if ($height==0) {
			return false;
		}
		$percent = $newHeight/$height;

		return Jpeg::resize_pct($percent, $filename, $out);
	}
	
	//resize images according to the targeted new height and new width. 
	//fits the smallest dimension (width or height) to  preserve aspect ratio
	//if the new dimensions are greater than the originals, just copy the original file
	public static function resize_both($newWidth,$newHeight,$filename,$out){
		list($width, $height) = getimagesize($filename);
		
		if ($height==0 || $width==0) {
			return false;
		}

		$percenth = $newHeight/$height;
		$percentw = $newWidth/$width;
		
		($percentw<$percenth)?$percent=$percentw:$percent=$percenth;
		
		return Jpeg::resize_pct($percent, $filename, $out);
	}

	//resize img according to the given reduction percentage
	//if the percentage is greater or equal to 1, then just copy the original image
	public static function resize_pct($percent,$filename, $out){	
		
		if ($percent>=1){
			return copy($filename, $out);
		}
		
		// Calcul des nouvelles dimensions
		list($width, $height) = getimagesize($filename);
		$newWidth = $width * $percent;
		$newHeight = $height * $percent;
		return Jpeg::resize_dim($width, $height, $newWidth, $newHeight, $filename, $out);
		
	}
	
	//common method to resize image
	private static function resize_dim($width, $height, $newWidth, $newHeight, $filename, $out){

		// Chargement
		$thumb = imagecreatetruecolor($newWidth, $newHeight);
		$source = imagecreatefromjpeg($filename);

		// Redimensionnement
		$resized = Jpeg::fastimagecopyresampled($thumb, $source,  0, 0, 0, 0, $newWidth, $newHeight, $width, $height, Jpeg::QUALITY);
		imagedestroy($source);
		if($resized==false){
			return false;
		}
				
		// enregistrement
		$saved = imagejpeg($thumb, $out);
		imagedestroy($thumb);
		
		if($saved==false){
			return false;
		}
		
		return true;
		
	}

	private static function fastimagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h, $quality){
	  // Plug-and-Play fastimagecopyresampled function replaces much slower imagecopyresampled.
	  // Just include this function and change all "imagecopyresampled" references to "fastimagecopyresampled".
	  // Typically from 30 to 60 times faster when reducing high resolution images down to thumbnail size using the default quality setting.
	  // Author: Tim Eckel - Date: 09/07/07 - Version: 1.1 - Project: FreeRingers.net - Freely distributable - These comments must remain.
	  //
	  // Optional "quality" parameter (defaults is 3). Fractional values are allowed, for example 1.5. Must be greater than zero.
	  // Between 0 and 1 = Fast, but mosaic results, closer to 0 increases the mosaic effect.
	  // 1 = Up to 350 times faster. Poor results, looks very similar to imagecopyresized.
	  // 2 = Up to 95 times faster.  Images appear a little sharp, some prefer this over a quality of 3.
	  // 3 = Up to 60 times faster.  Will give high quality smooth results very close to imagecopyresampled, just faster.
	  // 4 = Up to 25 times faster.  Almost identical to imagecopyresampled for most images.
	  // 5 = No speedup. Just uses imagecopyresampled, no advantage over imagecopyresampled.

	  
	  if (empty($src_image) || empty($dst_image) || $quality <= 0) { return false; }
	  if ($quality < 5 && (($dst_w * $quality) < $src_w || ($dst_h * $quality) < $src_h)) {
		$temp = imagecreatetruecolor($dst_w * $quality + 1, $dst_h * $quality + 1);
		imagecopyresized($temp, $src_image, 0, 0, $src_x, $src_y, $dst_w * $quality + 1, $dst_h * $quality + 1, $src_w, $src_h);
		imagecopyresampled($dst_image, $temp, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $dst_w * $quality, $dst_h * $quality);
		imagedestroy ($temp);
	  } else imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
	  return true;
	}
}

?>