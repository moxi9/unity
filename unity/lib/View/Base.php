<?php

namespace Unity\View;

class Base {
	private $path;

	public function __construct($path) {
		$this->path = $path;
	}

	public function render($name, $params = []) {
		$file = $this->path . $name;

		require($file);

		$content = ob_get_contents();
		ob_clean();

		return $content;
	}
}