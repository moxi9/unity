<?php

namespace Unity\Validation;

class Service {
	private $errors = [];

	public static $out = '';

	public function __construct(\Unity\Validation $validation, $post, $checks) {
		foreach ($checks as $name => $check) {
			foreach ($check as $type => $value) {
				switch ($type) {
					case 'required':
						if (empty($post->$name)) {
							$this->error($name, 'is_required', 'Field is required.');
						}
						break;
					default:
						event()->trigger()->validation_check()->using($this, $validation, $post, $checks);
						break;
				}
			}
		}

		if ($this->errors) {
			$out = '';
			foreach ($this->errors as $error) {
				$out .= '<div class="alert alert-danger">' . $error['phrase'] . '</div>';
			}

			self::$out = $out;
			error($out);
		}
	}

	public function error($name, $code, $phrase) {
		$this->errors[] = [
			'name' => $name,
			'code' => $code,
			'phrase' => $phrase
		];
	}
}