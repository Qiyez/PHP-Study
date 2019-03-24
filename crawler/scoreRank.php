<?php 

$url = 'http://www.zuihaodaxue.com/zuihaodaxuepaiming2019.html';
$outPageTxt = file_get_contents($url);
$dom = new DOMDocument();
// 从一个字符串加载HTML
@$dom->loadHTML($outPageTxt);
// 使该HTML规范化
$dom->normalize();
// 用DOMXpath加载DOM，用于查询
$xpath = new DOMXPath($dom);
// 获取标题
$data = $xpath->query('//thead/tr/th');
echo '<table align=center><tr>';
for ($i = 0; $i < $data->length-1; $i++) {
    $items = $data->item($i);
    $text = $items->nodeValue;
    echo '<th>'.$text.'</th>';
}
echo "</tr><tr>";

$data = $xpath->query('//tbody/tr/td[position()<5]');
for ($i = 0; $i < $data->length; $i++) {
    if ($i%4==0 && $i!=0) {
    	echo "</td><tr>";
    }
    $items = $data->item($i);
    $text = $items->nodeValue;
    echo '<td align=center>'.$text.'</td>';
}
 ?>