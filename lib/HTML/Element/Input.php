<?php

namespace Unity\HTML\Element;

/**
 * Class Input
 * @package Unity\HTML\Element
 *
 * @method \Unity\HTML\Service text($name, \Unity\Validation $validation = null)
 * @method \Unity\HTML\Service password($name, \Unity\Validation $validation = null)
 * @method \Unity\HTML\Service email($name, \Unity\Validation $validation = null)
 * @method \Unity\HTML\Service submit($name = null)
 */
class Input {
	private $service;

	public function __construct(\Unity\HTML\Service $service) {
		$this->service = $service;
	}

	public function __call($method, $args) {
		$name = (isset($args[0]) ? $args[0] : '');
		if ($this->service->__is_form) {
			$name = $this->service->__is_form->name . '[' . $name . ']';
		}

		if (isset($args[1])) {
			if ($args[1] instanceof \Unity\Validation) {
				$args[1]->__build($this->service->__is_form->name, $args[0], (string) $args[1]);
			}
		}

		$class = 'form-control';
		if ($method == 'submit') {
			$name = '__submit';
			$class = 'btn btn-primary';
		}

		$html = '';
		if ($method != 'submit') {
			$html .= '<div class="form-group">';
		}
		$html .= '<input type="' . $method . '" name="' . $name . '" data-unity="true" class="' . $class . '">';
		if ($method != 'submit') {
			$html .= '</div>';
		}

		$hash = md5($method . (isset($args[0]) ? $args[0] : ''));
		$this->service->__form[$hash] = $html;
		$this->service->__last = $hash;
		$this->service->__last_method = $method;

		return $this->service;
	}
}