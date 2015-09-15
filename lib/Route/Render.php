<?php

namespace Unity\Route;

class Render {
	private $app;

	public function __construct(\Unity\App $app) {
		$this->app = $app;
	}

	public function execute() {
		$uri = $this->app->request->uri();
		$r = Service::$__routes;
		$active = null;
		$response = false;
		$params = [];
		$request_method = strtolower($_SERVER['REQUEST_METHOD']);

		if (isset($r[$uri])) {
			$active = $r[$uri];
		}
		else {
			$parts = explode('/', trim($uri, '/'));
			foreach ($r as $route => $action) {
				if (strpos($route, ':')) {
					$segments = explode('/', trim($route, '/'));
					$activeRoute = false;
					$fail = false;

					foreach ($parts as $iteration => $segment) {

						if ($activeRoute === true && isset($segments[$iteration]) && substr($segments[$iteration], 0, 1) == ':') {

							if (!empty($active['where'])) {
								if (isset($active['where'][str_replace(':', '', $segments[$iteration])])) {
									$where = $active['where'][str_replace(':', '', $segments[$iteration])];
									if (!preg_match($where, $segment)) {
										$fail = true;
										break;
									}
								}
							}

							$params[] = $this->app->request->segment(($iteration + 1));

							continue;
						}

						if (isset($segments[$iteration]) && $segments[$iteration] == $segment) {
							$activeRoute = true;
							$active = $action;
						}

						if ($activeRoute) {
							if (!isset($segments[$iteration])) {
								$fail = true;
							}
						}
					}

					if ($fail === true) {
						$active = false;
					}
				}
			}
		}

		if ($active !== null) {
			if ($active['accept'] != 'any' &&
				(
					(is_string($active['accept']) && $active['accept'] != $request_method)
					|| (is_array($active['accept']) && !in_array($request_method, array_map('strtolower', $active['accept'])))
				)
			) {
				fatal('Request method "%s" is not allowed on this page.', $request_method);
			}

			if ($active['action'] instanceof \Closure) {
				$response = call_user_func_array($active['action'], $params);
			}
			else if (is_string($active['action'])) {
				list($class, $method) = explode('@', $active['action']);
				$object = new \ReflectionClass($class);
				$ref = $object->newInstanceWithoutConstructor();
				if (!method_exists($ref, $method)) {
					fatal('"%s" method does not exist for this route.', $method);
				}

				$response = call_user_func_array([$ref, $method], $params);

			}
		}

		return $response;
	}
}