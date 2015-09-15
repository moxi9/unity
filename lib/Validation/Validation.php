<?php

namespace Unity;

/**
 * Class Validation
 * @package Unity
 *
 * @method Validation required()
 * @method Validation min($length)
 * @method Validation max($length)
 */
class Validation {
	private $validations = [];
	private $recent = '';

	public function passed() {
		if ($this->validations
			&& request()->is_posted()
			&& ($post = request()->post())
			&& isset($this->validations[request()->token()])
		) {
			$post = request()->post();
			$validation = $this->validations[request()->token()];

			try {
				return new Validation\Service($this, $post, $validation);
			} catch (\Exception $e) {
				return false;
			}
		}

		return false;
	}

	public function __call($method, $args) {
		$this->recent[$method] = (isset($args[0]) ? $args[0] : '');

		return $this;
	}

	public function __build($form, $element, $params) {
		$form = (request()->token() ? request()->token() : md5($form));
		if (!isset($this->validations[$form])) {
			$this->validations[$form] = [];
		}
		$this->validations[$form][$element] = json_decode($params);
	}

	public function __toString() {
		return json_encode($this->recent);
	}
}