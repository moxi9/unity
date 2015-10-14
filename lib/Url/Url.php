<?php

namespace Unity;

class Url {
	public function make($route) {
		$url = 'http://localhost/unity/index.php/' . trim($route, '/');

		return $url;
	}

	public function send($route) {
		@ob_clean();

		header('Location: ' . $this->make($route));
		exit;
	}
}