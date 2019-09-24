<?php  

$host = '127.0.0.1';
$port = 11211;
$mem = new Memcache();
$mem->connect($host, $port);
$items = $mem->getExtendedStats('items');
$items = $items["$host:$port"]['items'];
foreach ($items as $key => $values) {
    $number = $key;;
    $str = $mem->getExtendedStats("cachedump", $number, 0);
    $line = $str["$host:$port"];
    if (is_array($line) && count($line) > 0) {
        echo "<table border=2>";
        echo "<tr><th>键</th><th>值</th></tr>";
        foreach ($line as $key => $value) {
            echo "<tr>";
            echo "<td style='width:30%'>";
            echo $key;
            echo "</td>";
            echo "<td style='width: 70%'>";
            var_dump($mem->get($key));
            echo "</td>";
            echo "</tr>";
         }
         echo "</table>";
     }
}