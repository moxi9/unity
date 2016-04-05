<?php

namespace Unity;

class Moment {
	public function boot() {
		define('UNITY_TIME_STAMP', time());
	}

	public function now() {
		return UNITY_TIME_STAMP;
	}
}