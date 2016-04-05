<?php

namespace Unity;

class Url {
	public function make($route) {
		$url = 'http://localhost/fashion/index.php/' . trim($route, '/');

		return $url;
	}

	public function send($route) {
		if (http()->is_ajax()) {
			return [
				'send' => $this->make($route)
			];
		}

		@ob_clean();

		header('Location: ' . $this->make($route));
		exit;
	}
}