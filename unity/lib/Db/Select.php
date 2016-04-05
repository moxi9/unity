<?php

namespace Unity\Db;

/**
 * Class Select
 * @package Unity\Db
 *
 * @method Select from($table, $alias = null)
 * @method Select get()
 * @method Select all()
 */
class Select {
	private $db;
	private $query;

	public function __construct(\Unity\Db $db, $select) {
		$this->db = $db;
		$this->query = $db->connection->createQueryBuilder();
		$this->query->select($select);
	}

	public function __call($method, $args) {

		if ($method == 'get' || $method == 'all') {
			$return = null;

			if ($method == 'get') {
				$return = $this->query->execute()->fetch();
			}
			else if ($method == 'all') {
				$return = $this->query->execute()->fetchAll();
			}

			return $return;
		}

		call_user_func_array([$this->query, $method], $args);

		return $this;
	}
}