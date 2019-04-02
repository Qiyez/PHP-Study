<?php 
$s = 'sdlkfjsldkfj.jpg';
$output = explode('.', $s);
var_dump($output);
$output = array_pop($output);
var_dump($output);