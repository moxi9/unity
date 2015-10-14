<?php

namespace Unity;

class Auth {
	public $key = '';
	public $is_active = false;

	public function login($id, $hash = null) {
		$token = md5(uniqid() . $hash);

		app()->cookie->set($this->key . 'user_id', $id);
		app()->cookie->set($this->key . 'token', $token);

		return $token;
	}

	public function logout() {
		app()->cookie->set($this->key . 'user_id', 0, -1);
	}

	public function active($redirect = false) {
		if (app()->cookie->get($this->key . 'user_id')) {
			$this->is_active = true;
		}

		event()->trigger()->auth_active()->using($this);

		if ($redirect === true && $this->is_active !== false) {
			app()->url->send('/login');
		}

		return $this->is_active;
	}

	public function id() {
		$id = app()->cookie->get($this->key . 'user_id');
		if (!$id) {
			return false;
		}

		return $id;
	}

	public function token() {
		return app()->cookie->get($this->key . 'token');
	}
}