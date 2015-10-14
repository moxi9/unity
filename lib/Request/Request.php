<?php

namespace Unity;

class Request {

	public function all() {
		return $_REQUEST;
	}

	public function get($name) {
		return (isset($_REQUEST[$name]) ? $_REQUEST[$name] : null);
	}

	public function is_posted() {
		if (!isset($_POST['__token'])) {
			return false;
		}

		return true;
	}

	public function token() {
		return (isset($_POST['__token']) ? $_POST['__token'] : '');
	}

	public function post($name = null) {
		if (!isset($_POST['__token'])) {
			error('Not a valid POST');
		}

		$post = [];
		$token = $_POST['__token'];
		if (strlen($token) != 32) {
			$token = md5($token);
		}

		foreach ($_POST as $key => $value) {
			if (md5($key) == $token) {
				$post = $value;
				break;
			}
		}

		if ($name === null) {
			return (count($post) ? (object) $post : []);
		}

		return (isset($post[$name]) ? $post[$name] : null);
	}

	public function uri() {
		$uri = '';
		$request = explode('index.php', $_SERVER['REQUEST_URI']);

		if (isset($request[1])) {
			$uri = $request[1];
			$uri = explode('?', $uri)[0];
		}
		$uri = rtrim($uri, '/');

		if (empty($uri)) {
			$uri = '/';
		}

		return $uri;
	}

	public function segment($count) {
		$uri = explode('/', trim($this->uri(), '/'));
		$count--;

		return (isset($uri[$count]) ? $uri[$count] : null);
	}

	public function __toString() {
		return '';
	}
}