<?php

namespace Unity;

/**
 * Class Route
 * @package Unity
 *
 * @method Route\Service get($route, $callback = null)
 * @method Route\Service post($route, $callback = null)
 * @method Route\Service delete($route, $callback = null)
 * @method Route\Service put($route, $callback = null)
 * @method Route\Service any($route, $callback = null)
 */
class Route {

	public function __call($method, $args) {
		if (is_string($args[0]) && strpos($args[0], ':')) {
			$command = explode(':', $args[0]);
			switch ($command[0]) {
				case 'scan':
					foreach (scandir($command[1]) as $file) {
						if (substr($file, -4) == '.php') {
							require($command[1] . $file);
						}
					}
					break;
			}
		}
		else if (is_string($args[0]) && substr($args[0], -4) == '.php' && file_exists($args[0])) {
			$routes = require($args[0]);
			if (is_array($routes)) {
				foreach ($routes as $route => $actions) {
					$this->__call('any', [$route, $actions]);
				}

				return null;
			}
		}

		if (is_array($args[1])) {
			$a = $args[1];
			if (isset($a['route'])) {
				$args[1] = $a['route'];
			}
			$r = route($args[0], $args[1]);
			if (isset($a['where'])) {
				call_user_func([$r, 'where'], $a['where']);
			}

			return null;
		}

		if ($method == 'any') {
			$parts = explode(' ', $args[0]);

			if (isset($parts[1])) {
				$method = array_map('trim', explode(',', $parts[0]));
			}

			$args[0] = (isset($parts[1]) ? $parts[1] : $parts[0]);
		}

		$service = new Route\Service($args[0], $args[1], $method);
		if (isset($parts) && isset($parts[2])) {
			$service->auth();
		}

		return $service;
	}
}