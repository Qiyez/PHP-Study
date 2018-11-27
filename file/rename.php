<?php 
// $path = 'preview';
// $handle = opendir($path);
// while ($file = readdir($handle)) {
// 	if ($file !== '.' && $file !== '..') {
// 		copy('preview/'.$file, 'result/'.$file.'.png');
// 	}
// }
// closedir($handle);
$n = 16;
for ($i=1; $i <= $n; $i++) { 
	echo "2的{$i}次方:".pow(2,$i).PHP_EOL;
}