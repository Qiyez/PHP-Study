<?php

$user_list = [
    ["num" => "02041601001", "pwd" => "C1021664319"],
    ["num" => "02041601004", "pwd" => "02041601004"],
    ["num" => "02041601006", "pwd" => "02041601006"],
    ["num" => "02041601008", "pwd" => "02041601008"],
    ["num" => "02041601009", "pwd" => "02041601009"],
    ["num" => "02041601015", "pwd" => "02041601015"],
    ["num" => "02041601024", "pwd" => "02041601024"],
    ["num" => "02041601031", "pwd" => "02041601031"],
    ["num" => "02041601040", "pwd" => "02041601040"],
    ["num" => "02041601056", "pwd" => "02041601056"],
    ["num" => "02041601057", "pwd" => "941028"],
];

function plog($str)
{
    echo date("H:i:s ") . $str . PHP_EOL;
}

class HttpClient
{
    private $ch;
    private $header = [];

    public function __construct()
    {
        $this->ch = curl_init();
    }

    public function set_header($k, $v)
    {
        $this->header[$k] = $v;
    }

    public function get_header()
    {
        $t = [];

        foreach ($this->header as $k => $item) {
            $t[] = $k . ': ' . $item;
        }

        return $t;
    }

    public function del_header($k)
    {
        unset($this->header[$k]);
    }

    public function set_header_array(array $header)
    {
        $this->header = array_merge($this->header, $header);
    }


    private $cookie = '';

    public function set_cookie($str)
    {
        $this->cookie = $str;
    }

    public function get($url, $get, $show_head = false)
    {

       if (!empty($get)) {
           $url .= '?' . http_build_query($get);
       }

        curl_setopt_array($this->ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,//接收返回的内容体,而不是直接输出
            CURLOPT_HEADER => $show_head,//不显示头部
            CURLOPT_TIMEOUT => 10,
            CURLOPT_COOKIE => $this->cookie,//cookie
            CURLOPT_HTTPHEADER => $this->get_header()
        ]);

        if (curl_errno($this->ch)) {
            throw new Exception("CURL Error: " . curl_error($this->ch));
        }
        $result = curl_exec($this->ch);
        //$info = curl_getinfo($ch);

        return $result;

    }

    public function post($url, $get, $post, $show_head = false)
    {
        if (!empty($get)) {
            $url .= '?' . http_build_query($get);
        }

        curl_setopt_array($this->ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,//接收返回的内容体,而不是直接输出
            CURLOPT_HEADER => $show_head,//不显示头部
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($post),
            CURLOPT_COOKIE => $this->cookie,//cookie
            CURLOPT_HTTPHEADER => $this->get_header()
        ]);

        if (curl_errno($this->ch)) {
            throw new Exception("CURL Error: " . curl_error($this->ch));
        }
        $result = curl_exec($this->ch);
        //$info = curl_getinfo($ch);

        return $result;
    }
}

echo '----------'.PHP_EOL.date("Y-m-d").PHP_EOL.'----------'.PHP_EOL;

$client = new HttpClient();
$client->set_header_array([
    "Accept-Encoding" => "gzip, deflate",
    "Accept-Language" => "zh-CN,zh;q=0.9",
    "User-Agent" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.81 Safari/537.36",
    "Content-Type" => "application/x-www-form-urlencoded; charset=UTF-8",
    "Accept" => "*/*",
]);


foreach ($user_list as $user) {

    $user_num = $user['num'];
    $user_pwd = $user['pwd'];

    plog('开始处理学生' . $user_num);

    try {
        #0 获取新的SESSION ID
        $client->set_cookie('');
        $result = $client->get('http://218.16.143.198:8008/suite/portal/portalView.do?siteKey=0&menuNavKey=0', [], true);
        preg_match("/JSESSIONID=([a-zA-Z0-9]+);\s/", $result, $match);

        if (empty($match[1])) {
            plog('获取SESSION ID失败');
            continue;
        }
        $session_id = $match[1];
        $client->set_cookie("JSESSIONID={$session_id};");
        #1 开始登录
        $client->set_header('x-requested-with', 'XMLHttpRequest');
        $result = $client->post(
            'http://218.16.143.198:8008/suite/login.do',
            ['randnum' => '0.' . mt_rand(100000000000000, 9999999999999999)],
            [
                "loginButton" => "login",
                "ajaxLogin" => "true",
                "loginName" => $user_num,
                "password" => base64_encode($user_pwd),
                "Submit2" => "登录",
                "Submit3" => "重置",
            ]
        );
        $result = trim($result, '|');
        if ($result == 'success') {
            plog('登录成功');
        } else {
            throw new Exception('登录失败');
        }

        ##2 重定向获取 practiceKey
        $result = $client->get('http://218.16.143.198:8008/suite/person/personView.do?menuKey=myPractice', [], true);
        preg_match("/Location:[\s\/\w=&\.\?]+practiceKey=(\d+)/i", $result, $match);
        if (empty($match[1]))
            throw new Exception('2 获取失败 practiceKey');

        $entityKey = $match[1];

        ##3签到检测
        $result = $client->get(
            'http://218.16.143.198:8008/suite/person/personView.do',
            ['feature' => 'person', 'action' => 'practiceing', 'practiceKey' => $entityKey],
            false
        );
        preg_match("/今天签到.+(\d+)\s?\/\s?(\d+).+次/", $result, $match);
        if (isset($match[1]) && $match[1] > 0) {
            plog('今日已签到');
            continue;
        }

        ##4 签到
        $result = $client->post(
            'http://218.16.143.198:8008/suite/signIn/saveSignIn.do',
            [],
            [
                "year" => intval(date('Y')),
                "month" => intval(date('m')) - 1,
                "daily" => intval(date('d')),
                "signTag" => "",
                "scopeKey" => "5202209",
                "scopeType" => "2",
                "entityKey" => $entityKey,
            ]
        );

        if ($result == 'success')
            plog('签到成功');


    } catch (Exception $e) {
        plog($e->getMessage());
        continue;
    }


}

