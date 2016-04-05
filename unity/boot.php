<?php

@ob_start();

set_error_handler(function() {

});

error_reporting(E_ALL);

$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) {
	exit('Missing /vendor/');
}

require($autoload);

$handler = new \Whoops\Handler\PrettyPageHandler;
$handler->setEditor('phpstorm');

$whoops = new \Whoops\Run;
$whoops->pushHandler($handler);

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

$site = start(function() {
	config()->storage(__DIR__ . '/_storage/');
	config()->vendor(__DIR__ . '/../vendor/');

	view()->path(__DIR__ . '/_theme/');

	echo "OK";
});

$site->render(function($response) {
	$vars = [];
	list($first, $info, $content) = explode("---", $response);
	foreach (explode("\n", trim($info)) as $line) {
		list($key, $value) = explode(':', $line);
		$vars[$key] = $value;
	}

	if (is_array($response) && isset($response['error'])) {
		$e = $response['error'];
		$response = '';
		foreach ($e as $error) {
			$response .= '<div class="message_error">' . $error . '</div>';
		}
		echo json_encode(['error' => $response], JSON_PRETTY_PRINT);
		exit;
	}
	else if (http()->is_ajax()) {
		if ($response === true) {
			$response = [
				'success' => true
			];
		}

		if (!is_string($response)) {
			echo json_encode($response, JSON_PRETTY_PRINT);
		}
		else {
			echo $response;
		}
		exit;
	}

	$route = request()->segment(1);
	if (empty($route)) {
		$route = 'home';
	}
	echo view('@theme/layout.html', [
		'page_id' => $route,
		'title' => (isset($vars['title']) ? $vars['title'] : ''),
		'version' => moment()->now(),
		// 'header' => asset()->get()->head,
		// 'footer' => asset()->get()->footer,
		'content' => $content
	]);
});