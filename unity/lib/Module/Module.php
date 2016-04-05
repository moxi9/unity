<?php

namespace Unity;

class Module {
	private $cache = [
		'view' => null
	];

	public function view($class = null) {
		if ($class === null) {
			return $this->cache['view'];
		}

		$this->cache['view'] = $class;
	}
}