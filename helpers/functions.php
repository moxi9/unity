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

		$message = vsprintf($message, array_values($args));
	}

	throw new Unity\Exception($message);
}

function config() {
	return app()->config;
}

function cache() {
	return app()->cache;
}

function request() {
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
 * @return \Unity\Validation
 */
function validation() {
	return app()->validation;
}

function d($out) {
	echo '<pre>';
	print_r($out);
	echo '</pre>';
}