<?php

namespace Unity;

/**
 * Class View
 * @package Unity
 *
 * @method render($name, $params = [])
 */
class View {
	private $base;
	private $path;

	public function path($path = null) {
		if ($path === null) {
			return $this->path;
		}

		$this->path = $path;
		$class = null;
		if (module()->view() !== null) {
			$object = new \ReflectionClass(module()->view());
			$this->base = $object->newInstance($path);
		}
		else {
			$this->base = new View\Base($this->path);
		}
	}

	public function __call($method, $args) {
		if (!method_exists($this->base, $method)) {
			fatal('"%s" method does not exist.', $method);
		}

		if ($this->base instanceof View\Template) {
			return call_user_func_array([$this->base, $method], $args);
		}

		fatal('Class is not an instance of View\\Template');
	}
}