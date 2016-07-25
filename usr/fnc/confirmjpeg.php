<?php
	$ConfirmJpeg = implode($argv," ");
	$im = imagecreatefromjpeg("../images/test.jpg");
	$black = ImageColorAllocate($im, 0, 0, 0);
	$px = (imagesx($im)-7.5*strlen($ConfirmJpeg))/2;
	ImageString($im,5,$px,10,$ConfirmJpeg,$black);
	ImageJPEG($im);
	ImageDestroy($im);
?>


