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
	private $driver = null;

	public function __call($method, $args) {
		if ($this->driver === null) {
			switch (config()->cache_driver()) {
				case 'memcached':
					$memcached = new \Memcached();
					$memcached->addServer(config()->cache_host(), 11211);

					$this->driver = new \Doctrine\Common\Cache\MemcachedCache();
					$this->driver->setMemcached($memcached);
					break;
				case 'apc':
					$this->driver = new \Doctrine\Common\Cache\ApcCache();
					break;
			}
		}

		switch ($method) {
			case 'set':
				// $this->driver->delete($args[0]);
				$this->driver->save($args[0], json_encode($args[1]));
				break;
			case 'get':
				return json_decode($this->driver->fetch($args[0]));
				break;
		}
	}
}