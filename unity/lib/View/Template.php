<?php

namespace Unity\View;

interface Template {
	public function __construct($path);
	public function render($name, $params = []);
}