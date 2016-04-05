<?php

namespace Unity\HTTP;

class Auth {
	public $user;
	public $pass;

	public function __construct() {
		$this->user = (isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null);
		$this->pass = (isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : $_SERVER['PHP_AUTH_PW']);
	}
}