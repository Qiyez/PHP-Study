<?php
//底图路径
$base_url = "./img/erzi.jpg";
//边框路径
$frame_url = "./img/frame_2.png";

//读取合并底图图片
$base = imagecreatefromjpeg($base_url);
//获取边框图片宽高度
list($src_w,$src_h) = getimagesize($base_url);
//读取边框图片
$frame = imagecreatefrompng($frame_url);
//获取边框图片宽高度
list($dst_w,$dst_h) = getimagesize($frame_url);

//创建真彩图
$merge_canvas = imageCreatetruecolor($src_w,$src_h);
//分配颜色
$color = imagecolorallocate($merge_canvas, 255, 255, 255);
//填充
imagefill($merge_canvas, 0, 0, $color);

/*
imagecopyresampled(
    新建的图片,
    需要载入的图片,
    设定需要载入的图片在新图中的x坐标,
    设定需要载入的图片在新图中的y坐标,
    设定载入图片要载入的区域x坐标,
    设定载入图片要载入的区域y坐标,
    设定载入的原图的宽度（在此设置缩放）,
    设定载入的原图的高度（在此设置缩放）,
    原图要载入的宽度,
    原图要载入的高度,
)
*/

//合并底图 
imagecopyresampled($merge_canvas, $base, 0, 0, 0, 0, $src_w, $src_h, $src_w, $src_h);
//合并边框
imagecopyresampled($merge_canvas, $frame, 0, 0, 0, 0, $src_w, $src_h, $dst_w, $dst_h);

imagejpeg($merge_canvas, "./img/flag_result.png");