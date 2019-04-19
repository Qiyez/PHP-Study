<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"> 
	<title>PHP-Study</title>
	<link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
	<script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="panel-group" id="accordion">
	<div class='container'>
	<h1>PHP-Study</h1>
<?php 
	$dir_handle = opendir('./');
	$num = 1;
	while ($dir_name = readdir($dir_handle)) {
		if (!preg_match("/^\..*/", $dir_name) && is_dir($dir_name)) {
			$dir_name2 = opendir($dir_name);

			echo"
<div class='panel panel-default'>
	<div class='panel-heading'>
		<h4 class='panel-title'>
			<a data-toggle='collapse' data-parent='#accordion' 
			   href='#collapse{$num}'>
				{$dir_name}
			</a>
		</h4>
	</div>
	<div id='collapse{$num}' class='panel-collapse collapse'>
	<div class='panel-body'>";

			while ($file_name = readdir($dir_name2)) {
				if (!preg_match("/^\..*/", $file_name)) {
					echo "<a href='{$dir_name}/{$file_name}'>{$file_name}</a><br>";
		
				}
			}

			echo"
		</div>
	</div>
</div>";
			$num++;
		}
	}

?>
	
	</div>
</div>

</body>
</html>
<?php

closedir($dir_handle);

 ?>