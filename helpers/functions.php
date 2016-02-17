<?php

function app() {
	return Unity\App::__instance();
}

function start(Closure $routine) {
	return new Unity\Site($routine);
}

function http() {
	return app()->http;
}

function module() {
	return app()->module;
}

function fatal($message, $args = null) {
	$args = func_get_args();
	if (count($args)) {
		$message = $args[0];
		unset($args[0]);
		$message = vsprintf($message, $args);
	}

	throw new Unity\Exception\Fatal($message);
}

function error($message) {
	$args = func_get_args();
	if (count($args)) {
		$message = $args[0];
		unset($args[0]);

		$message = (is_array($message) ? $message : vsprintf($message, array_values($args)));
	}

	throw new Unity\Exception($message);
}

function config($key = null, $default = null) {
	if ($key !== null) {
		return app()->config->get($key, $default);
	}

	return app()->config;
}

function cache() {
	return app()->cache;
}

function redis() {
	return app()->redis;
}

function cookie() {
	return app()->cookie;
}

function url($route = null) {
	if ($route !== null) {
		return app()->url->make($route);
	}

	return app()->url;
}

function request($name = null) {
	if ($name !== null) {
		return app()->request->get($name);
	}

	return app()->request;
}

function route($route = null, $action = null) {
	if ($route !== null) {
		return app()->route->any($route, $action);
	}

	return app()->route;
}

function view($name = null, $params = []) {
	if ($name === null) {
		return app()->view;
	}

	return app()->view->render($name, $params);
}

function finder() {
	return app()->finder;
}

function block($location = null, $route = null) {
	if ($location !== null) {
		return app()->block->make($location, $route);
	}
	return app()->block;
}

/**
 * @return \Unity\Db
 */
function db() {
	return app()->db;
}

/**
 * @return \Unity\Event
 */
function event() {
	return app()->event;
}

/**
 * @return \Unity\HTML
 */
function html() {
	return app()->html;
}

/**
 * @return \Unity\Asset
 */
function asset($path = null) {
	if ($path !== null) {
		return app()->asset->add($path);
	}

	return app()->asset;
}

function moment() {
	return app()->moment;
}

function auth() {
	return app()->auth;
}

/**
 * @return \Unity\Validation
 */
function validation(Closure $callback = null) {
	if ($callback !== null && $callback instanceof Closure) {
		if (app()->validation->passed() !== false) {
			return call_user_func($callback);
		}

		return null;
	}

	return app()->validation;
}

function d($out, $var_dump = false) {
	echo '<pre>';
	($var_dump ? var_dump($out) : print_r($out));
	echo '</pre>';
}