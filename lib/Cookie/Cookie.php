<?php

namespace Unity;

class Cookie {
	public function set($name, $value, $expire = 0) {
		setcookie($name, $value, $expire, '/', null, false, true);
	}

	public function get($name) {
		if (!isset($_COOKIE[$name])) {
			return null;
		}
		$value = $_COOKIE[$name];

		return $value;
	}
}