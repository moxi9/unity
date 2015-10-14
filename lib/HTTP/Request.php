<?php

namespace Unity\HTTP;

/**
 * Class Request
 * @package Unity\HTTP
 *
 * @method Request get($args = [])
 * @method Request post($args = [])
 * @method Request delete($args = [])
 * @method Request put($args = [])
 */
class Request {
	private $url;
	private $params = [];
	private $method = 'GET';
	private $data;

	public function __construct($url) {
		$this->url = $url;
	}

	public function __call($method, $args) {
		if (is_array($args) && isset($args[0])) {
			$this->params = $args[0];
		}

		$this->method = strtoupper($method);

		return $this->_send();
	}

	private function _send() {

		$url = $this->url;

		if (!is_array($this->params)) {
			$this->params = [$this->params];
		}
		$post = http_build_query($this->params);

		$curl_url = (($this->method == 'GET' && !empty($post)) ? $url . (strpos($url, '?') ? '&' : '?') . ltrim($post, '&') : $url);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $curl_url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		if ($this->method != 'GET' || $this->method != 'POST') {
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->method);
		}

		/*
		if ($this->_headers) {
			curl_setopt($curl, CURLOPT_HTTPHEADER, $this->_headers);
		}

		if ($this->_auth) {
			curl_setopt($curl, CURLOPT_USERPWD, $this->_auth[0] . ':' . $this->_auth[1]);
		}
		*/

		curl_setopt($curl, CURLOPT_TIMEOUT, 10);

		if ($this->method != 'GET') {
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
		}

		$this->data = curl_exec($curl);

		// var_dump($this->data); exit;

		curl_close($curl);

		$data = trim($this->data);
		if (substr($data, 0, 1) == '{' || substr($data, 0, 1) == '[') {
			$data = json_decode($data);
		}

		return $data;
	}
}