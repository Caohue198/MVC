<?php
	use mvc\Router;
	use mvc\Dispatcher;
	use mvc\Request;
	
	define('WEBROOT', str_replace("Webroot/index.php", "", $_SERVER["SCRIPT_NAME"]));
	define('ROOT', str_replace("Webroot/index.php", "", $_SERVER["SCRIPT_FILENAME"]));
	require __DIR__ . '/../vendor/autoload.php';

	$dispatch = new Dispatcher();
	$dispatch->dispatch();

?>