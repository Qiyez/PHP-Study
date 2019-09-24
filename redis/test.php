<?php
	//数据库连接
	$mysql_conf = array(
    'host'    => '127.0.0.1:3306', 
    'db'      => 'pm_dev', 
    'db_user' => 'root', 
    'db_pwd'  => '123456', 
    );

	$mysqli = @new mysqli($mysql_conf['host'], $mysql_conf['db_user'], $mysql_conf['db_pwd']);
	if ($mysqli->connect_errno) {
	    die("could not connect to the database:\n" . $mysqli->connect_error);//诊断连接错误
	}
	$mysqli->query("set names 'utf8';");//编码转化
	$select_db = $mysqli->select_db($mysql_conf['db']);

	if (!$select_db) {
	    die("could not connect to the db:\n" .  $mysqli->error);
	}

	//连接本地的 Redis 服务
	$redis = new Redis();
	$redis->connect('127.0.0.1', 6379);

	//获取get参数
	$id = isset($_GET['id']) ? $_GET['id'] : 1;

	if ($result = $redis->get("id_{$id}")) {
		var_dump(unserialize($result));
	} else {
		//数据库操作
		$sql = "select * from admin_user where id = {$id};";
		$res = $mysqli->query($sql);
		if (!$res) {
		    die("sql error:\n" . $mysqli->error);
		}
		$row = $res->fetch_assoc();
		$redis->set("id_{$id}",serialize($row),60);
		var_dump($row);
		$res->free();
	}
	
	$mysqli->close();