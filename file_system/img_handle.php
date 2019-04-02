<?php 
function image($filename, $string) {
	list($width, $height, $type) = getimagesize($filename);
	$types = array(1=>'git',2=>'jpeg',3=>'png');
	$createfrom = 'imagecreatefrom'.$types[$type];
	// 通过变量函数去找对应的GD库函数
	$image = $createfrom($filename);
	$x = ($width-imagefontwidth(20)*strlen($string))/2;
	$y = ($height-imagefontwidth(20))/2;
	$textcolor = imagecolorallocate($image, 255, 0, 0);
	imagestring($image, 100, $x, $y, $string, $textcolor);
	$output = 'image'.$types[$type];
	$output($image, $filename);
	imagedestroy($image);
}
image('example1.png','GIFF');