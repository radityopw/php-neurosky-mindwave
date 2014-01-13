<?php
set_time_limit (0);

require_once 'conf.php';
require_once 'mindwave.php';
require_once 'csv.php';

$total_argv = count($argv);

if($total_argv < 2) {
	echo('Filename Needed!');
	exit(2);
}

$filename = $argv[1];

if(file_exists($filename.'.tmprun')){
	echo('Another Process is Running with filename '.$filename);
	exit(2);
}

touch($filename);

$fp = fopen($filename.'.tmprun', 'w');
fwrite($fp, date('now'));
fclose($fp);


$sock = socket_create(AF_INET, SOCK_STREAM, 0);

if(!sock){

	echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
	exit(1);
}


$c = socket_connect($sock, $address, $port);

if(!$c){
	echo "socket_connect() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
	exit(1);
}

$param['enableRawOutput'] = false;
$param['format'] = "Json";

$sent = socket_write($sock, json_encode($param), strlen(json_encode($param)));

if(!$sent){
	echo "socket_write() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
	exit(1);
}

$con = 1;

while($con != 0){

	$input = "";
	
	while (($currentByte = socket_read($sock, 1,PHP_NORMAL_READ)) != "\r") {
	
		if (!file_exists($filename.'.tmprun')) {
			$con = 0;
			break;
		}
	
		if($currentByte != "\n" && $currentByte != "\r" && $currentByte != "\0"){
			$input.=$currentByte;
		}
		
	}
	
	if (!file_exists($filename.'.tmprun')) {
		
		$con = 0;
		break;
	}
	
	if($input != ""){
		$data = to_array($input);
		if($con == 1){
			add_header($filename,$data['header']);
		}
		add_data($filename,$data['data']);
	}
	
	$con++;
}


$close = socket_close($sock);