<?php 

$url = 'http://www.hao6v.com/gvod/zx.html';
$outPageTxt = file_get_contents($url);
$dom = new DOMDocument();
// 从一个字符串加载HTML
@$dom->loadHTML($outPageTxt);
// 使该HTML规范化
$dom->normalize();
// 用DOMXpath加载DOM，用于查询
$xpath = new DOMXPath($dom);
// 获取所有的a标签
$data = $xpath->query('//*[@id="main"]/div[1]/div/ul/li/a');
//取值方式一
foreach ($data as $val) {
    $item = $val->childNodes;
    foreach ($item as $v) {
        $str = trim($v -> textContent);
        echo $str . '<br/>';
    }
}
//取值方式二
for ($i = 0; $i < $data->length; $i++) {
    $items = $data->item($i);
    $text = $items->nodeValue;
    echo $text . '<br/>';
}
 ?>