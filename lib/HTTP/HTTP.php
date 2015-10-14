<?php

namespace Unity;

class HTTP {
	public function auth() {
		return new HTTP\Auth();
	}

	public function is_ajax() {
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			return true;
		}

		return false;
	}

	public function request($url) {
		return new HTTP\Request($url);
	}

	public function type($type) {
		$content = '';
		switch ($type) {
			case 'js':
				$content = 'application/javascript';
				break;
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