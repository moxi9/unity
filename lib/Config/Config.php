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
 * @method Config error_handler(\Closure $callback)
 *
 * @method Config cache_driver($driver = 'memcached')
 * @method Config cache_host($host = null)
 */
class Config {
	private $cache = [];

	public function boot() {
		$this->cache['vendor'] = __DIR__ . '/../../../../';
		if (!is_dir($this->cache['vendor'])) {
			fatal('Missing vendor path: %s', $this->cache['vendor']);
		}
	}

	public function set($key, $value) {
		$this->cache[$key] = $value;

		return $this;
	}

	public function get($key, $default = null) {
		return (isset($this->cache[$key]) ? $this->cache[$key] : $default);
	}

	public function register($namespace, $path) {
		spl_autoload_register(function($class) use($namespace, $path) {
			$class = str_replace('\\', '/', $class);
			$namespace = trim(str_replace('\\', '/', $namespace), '/');

			if (substr($class, 0, strlen($namespace)) == $namespace) {
				$name = substr_replace($class, '', 0, (strlen($namespace) + 1));
				$file = $path . $name . '.php';
				if (file_exists($file)) {
					require($file);
				}
			}
		});

		return $this;
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