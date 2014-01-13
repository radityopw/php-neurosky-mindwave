<?php

function add_data($filename,$data){
	$fp = fopen($filename, 'a');
	fputcsv($fp, $data);
	fclose($fp);
}

function add_header($filename,$data){
	$fp = fopen($filename, 'w');
	fputcsv($fp, $data);
	fclose($fp);
}