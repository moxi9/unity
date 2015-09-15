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
		}

		if (is_object($response) || is_array($response)) {
			http()->type('json');
			echo json_encode($response, JSON_PRETTY_PRINT);
		}
		else if ($response === false) {
			http()->type('404');
			echo 'Page not found.';
		}
		else {
			http()->type('html');
			echo $response;
		}
	}
}