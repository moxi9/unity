<?php

namespace Unity;

/**
 * Class Config
 * @package Unity
 *
 * @method Config storage($value = null)
 * @method Config vendor($value = null)
 *
 * @method Config db_name($name = null)
 * @method Config db_host($host = 'localhost')
 * @method Config db_user($user = null)
 * @method Config db_pass($password = null)
 * @method Config db_driver($driver = 'mysql')
 *
 * @method Config cache_driver($driver = 'memcached')
 * @method Config cache_host($host = null)
 */
class Config {
	private $cache = [];

	public function boot() {
		$this->cache['vendor'] = __DIR__ . '/../../../';
	}

	public function view($path = null) {
		if ($path === null) {
			return view()->path();
		}

		view()->path($path);

		return $this;
	}

	public function __call($method, $args) {
		if (!isset($args[0])) {
			if (!isset($this->cache[$method])) {
				fatal('"%s" is not set.', $method);
			}

			return $this->cache[$method];
		}

		$this->cache[$method] = $args[0];

		return $this;
	}
}