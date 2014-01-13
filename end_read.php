<?php

$total_argv = count($argv);

if($total_argv < 2) {
	echo('Filename Needed!');
	exit(2);
}

$filename = $argv[1];
unlink($filename.'.tmprun');