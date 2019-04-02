<?php 
$start_time = microtime(true);
$ch = curl_init();
$array = ['cz7x'];

foreach ($array as $name) {
	mkdir($name);

	for ($i=1; $i < 200; $i++) { 
		if ($i < 10) {
			$pages = '00'.$i;
		}else if ($i < 100) {
			$pages = '0'.$i;
		}else{
			$pages = $i;
		}

		$url = 'http://www.shuxue9.com/pep/'.$name.'/ebook/'.$i.'.html';

		curl_setopt_array($ch, [
			CURLOPT_URL => $url,
			CURLOPT_HEADER => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => [
		            'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.81 Safari/537.36',
		            "Accept: */*",
		            "content-type: application/x-www-form-urlencoded;"
		        ]
		]);
		$res = curl_exec($ch);

		$res = mb_convert_encoding($res,'UTF-8','GB2312');
		preg_match("/<h1[\s\w=\"]+>(.+)<span>/", $res, $match);
		$title = preg_replace("/\//", "", $match[1]);

		@$img = file_get_contents('http://www.shuxue9.com/pep/'.$name.'/ebook/'.$pages.'.jpg');
		if (!$img) {
			break;
		}
		file_put_contents('./'.$name.'/'.$pages.' '.$title.'.jpg', $img);
		
		echo $i.PHP_EOL;
	}
}

curl_close($ch);
$end_time = microtime(true);
echo PHP_EOL.($end_time-$start_time);
 ?> 