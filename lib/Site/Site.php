<?php

namespace Unity;

class Site {
	private $_app;
	
	public function __construct($callback = null) {
		$this->_app = new App();

		call_user_func($callback, $this->_app);

		$v = config()->vendor();
		foreach (scandir($v) as $vendor) {
			if ($vendor == '.' || $vendor == '..') {
				continue;
			}

			$d = $v . $vendor;
			if (!is_dir($d)) {
				continue;
			}
			foreach (scandir($d) as $name) {
				$path = $d . '/' . $name . '/unity.php';
				if (file_exists($path)) {
					$module = require($path);
					if ($module instanceof \Closure) {
						call_user_func($module);
					}
				}
			}
		}
	}
	
	public function render($callback = null) {
		try {
			$render = new Route\Render($this->_app);
			$response = $render->execute();
		} catch (Exception\Fatal $e) {
			d($e->getTraceAsString());
			exit('fatal: ' . $e->getMessage());
		} catch (Exception $e) {
			$response = $e->getMessage();
			if (app()->http->is_ajax()) {
				http_response_code(400);
				$response = [
					'error' => $e->getMessageArray()
				];
			}
			else if (($handler = config('error_handler'))) {
				$response = call_user_func($handler, $e);
			}
		}

		$uri = request()->uri();
		if (is_object($response) || is_array($response)) {
			http()->type('json');
		}
		else if (substr($uri, -3) == '.js') {
			http()->type('js');

			$url = url($uri);
			echo 'Unity.blocklet(\'' . $url . '\', ' . json_encode(['view' => $response]) . ');';
			exit;
		}
		else {
			if ($response === false) {
				http()->type('404');
				$response = 'Page not found.';
			}
			else {
				http()->type('html');
			}

			/*
			if ($response === null || app()->http->is_ajax()) {
				exit;
			}
			*/
		}

		if (is_callable($callback)) {
			return call_user_func($callback, $response);
		}

		return $response;
	}
}

/**
 * echo view('@theme/layout.html', [
'content' => $response
]);
 */