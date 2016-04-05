<?php

namespace Unity;

class Exception extends \Exception {
	private static $errors = [];

	public function __construct($error) {
		if (is_array($error)) {
			self::$errors = $error;
		}
	}

	public function getMessageArray() {
		return self::$errors;
	}
}