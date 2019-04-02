<?php 

$allowtype = array("gif", "png", "jpg");
$size = 1000000;
$path = "./uploads";

if ($_FILES['myfile']['error'] > 0) {
	echo "上传错误： ";
	switch ($_FILES['myfile']['error']) {
		case 1: die('上传文件超出PHP限制');
		case 2: die('上传文件超出表单约定值');
		case 3: die('文件只被部分上载');
		case 4: die('没有文件上传');
		defualt: die('位置错误');
	}
}
$hz = @array_pop(explode(".", $_FILES['myfile']['name']));

if (!in_array($hz, $allowtype)) {
	die("不允许{$hz}类型上传");
}

if ($_FILES['myfile']['size'] > $size) {
	die('超过允许大小');
}

$file_name = date('YmdHis').'.'.$hz;

// 判断是否为上传文件
if (is_uploaded_file($_FILES['myfile']['tmp_name'])) {
	if (!move_uploaded_file($_FILES['myfile']['tmp_name'], $path.'/'.$file_name)) {
		die('问题：不能将文件移动到指定目录');
	}
}else{
	die('问题：上传文件'.$_FILES['myfile']['name'].'不是一个合法文件');
}

echo "文件{$_FILES['myfile']['name']}上传成功，保存在{$path}, 大小为{$_FILES['myfile']['size']}字节";