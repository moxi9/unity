<?php

namespace Unity\HTML;

/**
 * Class Service
 * @package Unity\HTML
 *
 * @method Service title($title)
 * @method Service placeholder($placeholder)
 * @method Service value($value)
 *
 */
class Service {
	public $__form = [];
	public $__last = '';
	public $__last_method = '';
	public $__is_form = false;

	private $html;

	public function __construct(\Unity\HTML $html) {
		$this->html = $html;
	}

	/**
	 * @return \Unity\HTML
	 */
	public function end() {
		return $this->html;
	}

	public function __call($method, $args) {
		if (isset($this->__form[$this->__last])) {
			switch ($method) {
				case 'title':
					if ($this->__last_method == 'submit') {
						$this->__form[$this->__last] = '' . str_replace('data-unity="true"', 'data-unity="true" value="' . $args[0] . '"', $this->__form[$this->__last]) . '';
					}
					else {
						$this->__form[$this->__last] = '<label>' . (isset($args[0]) ? $args[0] : '') . '</label>' . $this->__form[$this->__last] . '';
					}
					break;
				default:
					$value = (isset($args[0]) ? $args[0] : '');
					$this->__form[$this->__last] = str_replace('data-unity="true"', 'data-unity="true" ' . $method . '="' . $value . '"', $this->__form[$this->__last]);
					break;
			}
		}

		return $this;
	}

	public function __toString() {
		$html = '';
		if ($this->__is_form) {
			if (!validation()->passed()) {
				$html .= \Unity\Validation\Service::$out;
			}

			$html .= '<form method="' . $this->__is_form->method . '" action="' . $this->__is_form->action . '">';
			$html .= '<div><input type="hidden" name="__token" value="' . $this->__is_form->token . '"></div>';
		}
		$html .= implode('', $this->__form);
		if ($this->__is_form) {
			$html .= '</form>';
		}

		return $html;
	}
}