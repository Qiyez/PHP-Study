<?php 
$path = 'preview';
$handle = opendir($path);
while ($file = readdir($handle)) {
	if ($file !== '.' && $file !== '..') {
		copy('preview/'.$file, 'result/'.$file.'.png');
	}
}
closedir($handle);