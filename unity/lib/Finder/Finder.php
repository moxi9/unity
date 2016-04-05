<?php

namespace Unity;

/**
 * Class Finder
 * @package Unity
 *
 * @method Finder files()
 * @method Finder name($name)
 * @method Finder depth($length)
 */
class Finder {
	/**
	 * @var \Symfony\Component\Finder\Finder
	 */
	private $finder;

	public function boot() {
		$this->finder = new \Symfony\Component\Finder\Finder();
	}

	/**
	 * @param array|string $dir
	 *
	 * @return \Symfony\Component\Finder\SplFileInfo[]
	 */
	public function in($dir) {
		return $this->finder->in($dir);
	}

	public function __call($method, $args) {
		return call_user_func_array([$this->finder, $method], $args);
	}
}