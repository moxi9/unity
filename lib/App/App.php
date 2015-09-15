<?php

namespace Unity;

class App {
	/**
	 * @var Db
	 */
	public $db;

	/**
	 * @var Cache
	 */
	public $cache;

	/**
	 * @var Request
	 */
	public $request;

	/**
	 * @var Route
	 */
	public $route;

	/**
	 * @var View
	 */
	public $view;

	/**
	 * @var HTTP
	 */
	public $http;

	/**
	 * @var Module
	 */
	public $module;

	/**
	 * @var Config
	 */
	public $config;

	/**
	 * @var HTML
	 */
	public $html;

	/**
	 * @var Validation
	 */
	public $validation;

	/**
	 * @var Event
	 */
	public $event;

	/**
	 * @var App
	 */
	private static $object;

	public function __construct() {
		foreach ($this as $key => $value) {
			$this->{$key} = (new \ReflectionClass('\\Unity\\' . ucwords($key)))->newInstanceWithoutConstructor();
		}

		foreach ($this as $key => $value) {
			$obj = $this->{$key};
			if (method_exists($obj, 'boot')) {
				$obj->boot($this);
			}
		}

		self::$object = $this;
	}

	public static function __instance() {
		return self::$object;
	}
}