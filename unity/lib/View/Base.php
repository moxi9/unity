<?php

namespace Unity\View;

class Base implements Template {
	private $path;

	public function __construct($path) {
		$this->path = $path;
	}

	public function render($name, $params = []) {
		echo $name;

		return $content;
	}
}