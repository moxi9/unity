<?php

namespace Unity\Db;

class Delete {
	private $db;
	private $params;

	public function __construct(\Unity\Db $db, $params) {
		$this->db = $db;
		$this->params = $params;
	}

	public function in($table) {
		return $this->db->connection->delete($table, $this->params);
	}
}