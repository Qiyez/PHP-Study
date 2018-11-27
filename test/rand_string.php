<?php 
$link = mysqli_connect('119.29.119.156','lizhi','Li.123456','study_test');
mysqli_set_charset($link, 'utf8');

$str1="qwertyuiopasdfghjklzxcvbnm";
$str2="QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";

for ($i=0; $i < 100000; $i++) { 
	$cn = '';

	$name = substr(str_shuffle($str1), 1, rand(6,12));
	$token = substr(str_shuffle($str2), 1, 32);
	$t_number = rand(10,strlen($str2));
	$content = substr(str_shuffle($str2), 1, rand(10,$t_number));
	$rand_cn_number = rand(10,666);
	for ($j=0; $j<$rand_cn_number; $j++) {
	    // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
	    $a = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
	    // 转码
	    $cn .= iconv('GB2312', 'UTF-8', $a);
	}
	$sql = "insert into t_rand2(`username`, `token`, `content`,`t_number`,`t_cn`) values('{$name}','{$token}','{$content}','{$t_number}','{$cn}')";
	$result = mysqli_query($link, $sql);
}

// $sql = "insert into t_rand(`name`, `token`, `content`) values('asdfssdfdf','asdfssdfdfsdf','sdfsdfsdfsadf')";
// $result = mysqli_query($link, $sql);

// var_dump(mysqli_fetch_all($result,MYSQLI_ASSOC));

mysqli_close($link);