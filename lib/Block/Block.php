<?php

namespace Unity;

class Block {
	private $_blocks = [];

	public function boot() {

	}

	public function make($location, $route) {
		if (!isset($this->_blocks[$location])) {
			$this->_blocks[$location] = [];
		}

		$this->_blocks[$location][] = $route;

		return $this;
	}

	public function all($location) {
		$html = '';
		if (!isset($this->_blocks[$location])) {
			return $html;
		}

		foreach ($this->_blocks[$location] as $block) {
			$html .= '<div class="blocklet" data-url="' . url($block) . '.js"></div>';
		}

		return $html;
	}
}