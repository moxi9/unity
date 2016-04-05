<?php

namespace Unity\Db;

class Update {
	private $db;
	private $params = [];
	private $where = [];

	public function __construct(\Unity\Db $db, $params) {
		$this->db = $db;
		$this->params = $params;
	}

	public function where(array $where) {
		$this->where = $where;

		return $this;
	}

	public function in($table) {
		return $this->db->connection->update($table, $this->params, $this->where);
	}
}