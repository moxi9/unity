<?php

namespace Unity;

class Asset {
	public $__paths = [];

	public $version;

	public function boot() {

	}

	public function add($path) {
		$this->__paths[] = $path;

		return $this;
	}

	public function get() {
		return new Asset\Get($this);
	}
}