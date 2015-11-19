<?php
$file = fopen("workfile", "r");

while(!feof($file)){
$line = fgets($file, 100024);
//print_r($line."\n");
$line = trim(preg_replace('/\s+/', ' ', $line));
	$json = json_decode($line,true);
	print_r($json);
}

?>
