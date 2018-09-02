<?php 
/**
 * 用GD库转换图片格式
 */
function transform($file,$result){
	$img=imagecreatefrompng($file);
	imagejpeg($img,$result);
}