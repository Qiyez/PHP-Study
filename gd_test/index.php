<?php 
include "class/compression.php";
include "function/getsize.php";
include "function/transform_format.php";

$source =  './img/sr01.png';//原图片名称
$dst_img = './img/result.png';//压缩后图片的名称
$dst_img_jpg = './img/result.jpg';
$percent = 1;  #原图压缩，不缩放，但体积大大降低

$image = (new imgcompress($source,$percent))->compressImg($dst_img);

// png转jpg
transform($source,$dst_img_jpg);

$size = getSize($source);
$size_result = getSize($dst_img);
$size_result_jpg = getSize($dst_img_jpg);



echo "<img src=$source width='945' height='470'>原始png: $size<br>";
echo "<img src=$dst_img width='945' height='470'>压缩后png: $size_result";
echo "<img src=$dst_img_jpg width='945' height='470'>转换为jpg: $size_result_jpg";

 ?>