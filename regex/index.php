<?php  
$str = file_get_contents('36/model.obj');

$regex="/g Object059[\s\w\/]+\s+# \d+ polygons \- \d+ triangles/"; 
 
$result = preg_replace($regex,"",$str); 

file_put_contents('36/model1.obj', $result);