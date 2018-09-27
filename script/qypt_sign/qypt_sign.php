<?php

$user_list = [
    ["num" => "02041601004", "pwd" => "02041601004"],
    ["num" => "02041601008", "pwd" => "02041601008"],
    ["num" => "02041601009", "pwd" => "02041601009"],
    ["num" => "02041601015", "pwd" => "02041601015"],
    ["num" => "02041601024", "pwd" => "02041601024"],
    ["num" => "02041601031", "pwd" => "02041601031"],
    ["num" => "02041601040", "pwd" => "02041601040"],
    ["num" => "02041601056", "pwd" => "02041601056"],
    ["num" => "02041601057", "pwd" => "941028"],
];


$ch = curl_init();

function plog($str)
{
    echo date("H:i:s ") . $str . PHP_EOL;
}

foreach ($user_list as $user) {

    $user_num = $user['num'];
    $user_pwd = $user['pwd'];

    plog('开始处理学生' . $user_num);

    ##################################

    #0 获取新的SESSION ID

    curl_setopt($ch, CURLOPT_URL, 'http://218.16.143.198:8008/suite/portal/portalView.do?siteKey=0&menuNavKey=0');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,//接收返回的内容体,而不是直接输出
        CURLOPT_HEADER => true,//不显示头部
        CURLOPT_TIMEOUT => 10,
        CURLOPT_COOKIE => '',//cookie
        CURLOPT_HTTPHEADER => [
            'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.81 Safari/537.36',
            "Accept: */*",
            "Content-Type: application/x-www-form-urlencoded; charset=UTF-8"
        ]
    ]);

    if (curl_errno($ch)) {
        plog("CURL Error: " . curl_error($ch));
        continue;
    }
    $result = curl_exec($ch);
    $info = curl_getinfo($ch);
    preg_match("/JSESSIONID=([a-zA-Z0-9]+);\s/", $result, $match);

    if (empty($match[1])) {
        plog('获取SESSION ID失败');
        continue;
    }

    $session_id = $match[1];


    //plog('SESSEION ID 获取成功');


    ###############################

    #1 开始登录

    curl_setopt($ch, CURLOPT_URL, 'http://218.16.143.198:8008/suite/login.do?' . http_build_query(['randnum' => '0.' . mt_rand(100000000000000, 9999999999999999)]));
    curl_setopt_array($ch, [
        //CURLOPT_PROXY => '127.0.0.1:8888',
        CURLOPT_RETURNTRANSFER => true,//接收返回的内容体,而不是直接输出
        CURLOPT_HEADER => false,//不显示头部
        CURLOPT_TIMEOUT => 10,
        CURLOPT_POST => true,//似乎可以不写
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => http_build_query([
            "loginButton" => "login",
            "ajaxLogin" => "true",
            "loginName" => $user_num,
            "password" => base64_encode($user_pwd),
            "Submit2" => "登录",
            "Submit3" => "重置",
        ]),//post参数
        CURLOPT_COOKIE => "JSESSIONID={$session_id};",//cookie
        CURLOPT_HTTPHEADER => [
            "x-requested-with: XMLHttpRequest",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.81 Safari/537.36",
            "Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
            "Accept: */*",
            "Referer: http://218.16.143.198:8008/suite/portal/portalView.do;jsessionid={$session_id}?siteKey=0",
            "Accept-Encoding: gzip, deflate",
            "Accept-Language: zh-CN,zh;q=0.9"
        ]
    ]);


    if (curl_errno($ch)) {
        plog("CURL Error: " . curl_error($ch));
        continue;
    }
    $result = trim(curl_exec($ch), '|');
    $info = curl_getinfo($ch);

    if($result=='success'){
        plog('登录成功');
    }

    ##2 重定向获取 practiceKey
    //plog('重定向获取 practiceKey');
    curl_setopt($ch, CURLOPT_URL, 'http://218.16.143.198:8008/suite/person/personView.do?menuKey=myPractice');
    curl_setopt_array($ch, [
        //CURLOPT_PROXY => '127.0.0.1:8888',
        CURLOPT_RETURNTRANSFER => true,//接收返回的内容体,而不是直接输出
        CURLOPT_HEADER => true,//不显示头部
        CURLOPT_TIMEOUT => 10,
        CURLOPT_POST => true,//似乎可以不写
        CURLOPT_COOKIE => "JSESSIONID={$session_id};",//cookie
        CURLOPT_HTTPHEADER => [
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.81 Safari/537.36",
            "Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
            "Accept: */*",
            "Referer: http://218.16.143.198:8008/suite/portal/portalView.do;jsessionid={$session_id}?siteKey=0",
            "Accept-Encoding: gzip, deflate",
            "Accept-Language: zh-CN,zh;q=0.9"
        ]
    ]);


    if (curl_errno($ch)) {
        plog("CURL Error: " . curl_error($ch));
        continue;
    }
    $result = curl_exec($ch);
    $info = curl_getinfo($ch);

    //plog($result.PHP_EOL);


    preg_match("/Location:[\s\/\w=&\.\?]+practiceKey=(\d+)/i", $result, $match);

    if (empty($match[1])) {
        plog('获取失败');
        continue;
    }

    $entityKey = $match[1];


    ##3签到检测

    curl_setopt($ch, CURLOPT_URL, 'http://218.16.143.198:8008/suite/person/personView.do?feature=person&action=practiceing&practiceKey=5653376');
    curl_setopt_array($ch, [
        //CURLOPT_PROXY => '127.0.0.1:8888',
        CURLOPT_RETURNTRANSFER => true,//接收返回的内容体,而不是直接输出
        CURLOPT_HEADER => true,//不显示头部
        CURLOPT_TIMEOUT => 10,
        CURLOPT_POST => true,//似乎可以不写
        CURLOPT_COOKIE => "JSESSIONID={$session_id};",//cookie
        CURLOPT_HTTPHEADER => [
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.81 Safari/537.36",
            "Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
            "Accept: */*",
            "Referer: http://218.16.143.198:8008/suite/portal/portalView.do;jsessionid={$session_id}?siteKey=0",
            "Accept-Encoding: gzip, deflate",
            "Accept-Language: zh-CN,zh;q=0.9"
        ]
    ]);


    if (curl_errno($ch)) {
        plog("CURL Error: " . curl_error($ch));
        continue;
    }
    $result = curl_exec($ch);
    $info = curl_getinfo($ch);


    preg_match("/今天签到.+(\d+)\s?\/\s?(\d+).+次/", $result, $match);

    if (isset($match[1]) && $match[1] > 0) {
        plog('今日已签到');
        continue;
    }


    ##4 签到

    curl_setopt($ch, CURLOPT_URL, 'http://218.16.143.198:8008/suite/signIn/saveSignIn.do');
    curl_setopt_array($ch, [
        //CURLOPT_PROXY => '127.0.0.1:8888',
        CURLOPT_RETURNTRANSFER => true,//接收返回的内容体,而不是直接输出
        CURLOPT_HEADER => false,//不显示头部
        CURLOPT_TIMEOUT => 10,
        CURLOPT_POST => true,//似乎可以不写
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => http_build_query([
            "year" => intval(date('Y')),
            "month" => intval(date('m')) - 1,
            "daily" => intval(date('d')),
            "signTag" => "",
            "scopeKey" => "5202209",
            "scopeType" => "2",
            "entityKey" => $entityKey,
        ]),//post参数
        CURLOPT_COOKIE => "JSESSIONID={$session_id};",//cookie
        CURLOPT_HTTPHEADER => [
            "x-requested-with: XMLHttpRequest",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.81 Safari/537.36",
            "Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
            "Accept: */*",
            "Referer: http://218.16.143.198:8008/suite/portal/portalView.do;jsessionid={$session_id}?siteKey=0",
            "Accept-Encoding: gzip, deflate",
            "Accept-Language: zh-CN,zh;q=0.9"
        ]
    ]);

    if (curl_errno($ch)) {
        plog("CURL Error: " . curl_error($ch));
        continue;
    }
    $result = trim(curl_exec($ch));
    $info = curl_getinfo($ch);

    if($result=='success'){
        plog('签到成功');
    }


}

curl_close($ch);
