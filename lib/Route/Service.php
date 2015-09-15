<?php

namespace Unity\Route;

/**
 * Class Service
 * @package Unity\Route
 *
 * @method Service accept($methods)
 * @method Service where($clause)
 */
class Service {
	public static $__routes = [];

	private $route;

	public function __construct($route, $action, $method) {
		$this->route = $route;
		self::$__routes[$route] = [
			'action' => $action,
			'accept' => $method
		];
	}

	public function __call($method, $args) {
		self::$__routes[$this->route][$method] = $args[0];

		return $this;
	}
}