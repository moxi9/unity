<?php

namespace Unity\Db;

class Insert {
	private $db;
	private $params;

	public function __construct(\Unity\Db $db, $params) {
		$this->db = $db;
		$this->params = $params;
	}

	public function into($table) {
		$this->db->connection->insert($table, $this->params);

		return $this->db->connection->lastInsertId();
	}
}