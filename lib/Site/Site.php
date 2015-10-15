<?php

namespace Unity;

class Site {
	public function __construct($callback = null) {
		$app = new App();
		$response = false;

		try {
			foreach (scandir(config()->vendor()) as $vendor) {
				$path = config()->vendor() . $vendor . '/unity.php';
				if (file_exists($path)) {
					$module = require($path);

					if ($module instanceof \Closure) {
						call_user_func($module);
					}
				}
			}

			call_user_func($callback, $app);

			$render = new Route\Render($app);
			$response = $render->execute();
		} catch (Exception\Fatal $e) {
			d($e->getTraceAsString());
			exit('fatal: ' . $e->getMessage());
		} catch (Exception $e) {
			$response = $e->getMessage();
			if (app()->http->is_ajax()) {
				http_response_code(400);
				$response = [
					'error' => $e->getMessage()
				];
			}
			else if (($handler = config('error_handler'))) {
				$response = call_user_func($handler, $e);
			}
		}

		if (is_object($response) || is_array($response)) {
			http()->type('json');
			echo json_encode($response, JSON_PRETTY_PRINT);
		}
		else {
			if ($response === false) {
				http()->type('404');
				$response = 'Page not found.';
			}
			else {
				http()->type('html');
			}

			if ($response === null || app()->http->is_ajax()) {
				exit;
			}

			echo view('@theme/layout.html', [
				'content' => $response
			]);
		}
	}
}