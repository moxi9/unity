<?php

@ob_start();

set_error_handler(function() {

});

error_reporting(E_ALL);

if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
	require(__DIR__ . '/../../vendor/autoload.php');
}

$handler = new \Whoops\Handler\PrettyPageHandler;
$handler->setEditor('phpstorm');

$whoops = new \Whoops\Run;
$whoops->pushHandler($handler);
$whoops->register();

require(__DIR__ . '/helpers/functions.php');

spl_autoload_register(function($class) {
	if (substr($class, 0, 6) != 'Unity\\') {
		return;
	}

	$name = str_replace('\\', '/', $class);
	$name = str_replace('Unity/', '', $name);
	$file = $name;

	$path = __DIR__ . '/lib/' . $name . '/' . $file . '.php';
	if (!file_exists($path)) {
		$path = __DIR__ . '/lib/' . $file . '.php';
	}

	require($path);
});