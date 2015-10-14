<?php

namespace Unity\Event;

/**
 * Class Service
 * @package Unity\Event
 *
 * @method Service validation_check(\Closure $callback = null)
 * @method Service auth_active(\Closure $callback = null)
 */
class Service {
	public $events = [];

	private $last = null;

	public function using() {
		$args = func_get_args();
		$name = $this->last;
		$this->last = null;

		if (isset($this->events[$name])) {
			foreach ($this->events[$name] as $callback) {
				call_user_func_array($callback, $args);
			}
		}
	}

	public function __call($name, $args = []) {
		$callback = (isset($args[0]) ? $args[0] : null);
		if ($callback === null) {
			$this->last = $name;

			return $this;
		}

		if (!isset($this->events[$name])) {
			$this->events[$name] = [];
		}
		$this->events[$name][] = $callback;

		return null;
	}
}