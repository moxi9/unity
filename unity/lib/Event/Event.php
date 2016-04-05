<?php

namespace Unity;

class Event {
	private $service;

	public function boot() {
		$this->service = new Event\Service();
	}

	/**
	 * @return Event\Service
	 */
	public function on() {
		return $this->service;
	}

	/**
	 * @return Event\Service
	 */
	public function trigger() {
		return $this->service;
	}
}