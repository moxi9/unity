<?php

namespace Unity;

/**
 * Class HTML
 * @package Unity
 *
 * @property HTML\Element\Input $input
 *
 */
class HTML {

	private $service;

	public function boot() {
		$this->service = new HTML\Service($this);
	}

	/**
	 * @param $action
	 * @param null $name
	 * @param string $method
	 * @return HTML
	 */
	public function form($action, $name = null, $method = 'POST') {
		if ($name === null) {
			$name = uniqid();
		}
		$this->service->__is_form = (object) [
			'name' => $name,
			'token' => md5($name),
			'action' => $action,
			'method' => $method
		];

		return $this;
	}

	public function __get($method) {
		return (new \ReflectionClass('\\Unity\HTML\\Element\\' . ucwords($method)))->newInstance($this->service);
	}
}