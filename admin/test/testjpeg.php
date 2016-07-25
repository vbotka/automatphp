<?php
//	Header("Content-type: image/jpeg");
	$string=implode($argv," ");
	$im = imagecreatefromjpeg("../images/test.jpg");
//	$orange = ImageColorAllocate($im, 220, 210, 60);
	$black = ImageColorAllocate($im, 0, 0, 0);
	$px = (imagesx($im)-7.5*strlen($string))/2;
	ImageString($im,5,$px,10,$string,$black);
	ImageJPEG($im);
	ImageDestroy($im);
?>

