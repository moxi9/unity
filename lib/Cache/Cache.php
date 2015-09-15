<?php

namespace Unity;

/**
 * Class Cache
 * @package Unity
 *
 * @method Cache set($key, $value)
 * @method Cache get($key)
 */
class Cache {
	public function __call($method, $args) {
		$driver = null;
		switch (config()->cache_driver()) {
			case 'memcached':
				$memcached = new \Memcached();
				$memcached->addServer(config()->cache_host(), 11211);

				$driver = new \Doctrine\Common\Cache\MemcachedCache();
				$driver->setMemcached($memcached);
				break;
			case 'apc':
				$driver = new \Doctrine\Common\Cache\ApcCache();
				break;
		}

		switch ($method) {
			case 'set':
				// $driver->delete($args[0]);
				$driver->save($args[0], json_encode($args[1]));
				break;
			case 'get':
				return json_decode($driver->fetch($args[0]));
				break;
		}
	}
}