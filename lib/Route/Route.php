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
		if ($method == 'any') {
			$parts = explode(' ', $args[0]);

			if (isset($parts[1])) {
				$method = array_map('trim', explode(',', $parts[0]));
			}

			$args[0] = (isset($parts[1]) ? $parts[1] : $parts[0]);
		}

		return new Route\Service($args[0], $args[1], $method);
	}
}