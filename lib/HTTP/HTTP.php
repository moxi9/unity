<?php

namespace Unity;

class HTTP {
	public function boot() {

	}

	public function type($type) {
		$content = '';
		switch ($type) {
			case 'json':
				$content = 'application/json';
				break;
			case 'html':
				$content = 'text/html';
				break;
			case '404':
				$content = 'text/html';
				header('HTTP/1.0 404 Not Found');
				break;
		}

		header('Content-type: ' . $content);
	}
}