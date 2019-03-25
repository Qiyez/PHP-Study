<?php 

$url = "https://www.huya.com/g/lol";
$res = file_get_contents($url);
$dom = new DOMDocument();
@$dom->loadHTML($res);
$xpath = new DOMXPath($dom);
$dataHref = $xpath->query('//ul[@id="js-live-list"]/li/a[@class="title new-clickstat"]/@href');
$dataTitle = $xpath->query('//ul[@id="js-live-list"]/li/a[@class="title new-clickstat"]');
$dataZB = $xpath->query('//ul[@id="js-live-list"]/li/span/span/i[@class="nick"]');
$dataHot = $xpath->query('//ul[@id="js-live-list"]/li/span/span/i[@class="js-num"]');

echo '<table>';
for ($i = 0; $i < $dataHref->length-1; $i++) {
    $href = $dataHref->item($i)->nodeValue;
    $title = $dataTitle->item($i)->nodeValue;
    $zb = $dataZB->item($i)->nodeValue;
    $hot = $dataHot->item($i)->nodeValue;
    echo "<tr><td><a href={$href}>{$title}</a></td><td>{$zb}</td><td>{$hot}</td></tr>";
}
 ?>