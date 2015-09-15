<?php

namespace Unity;

/**
 * Class Db
 * @package Unity
 *
 */
class Db {
	/**
	 * @var \Doctrine\DBAL\Connection
	 */
	public $connection = null;

	public function connect() {
		if ($this->connection === null) {
			$connection_params = array(
				'dbname' => config()->db_name(),
				'user' => config()->db_user(),
				'password' => config()->db_pass(),
				'host' => config()->db_host(),
				'driver' => 'pdo_mysql',
			);
			$this->connection = \Doctrine\DBAL\DriverManager::getConnection($connection_params, new \Doctrine\DBAL\Configuration());
			$this->connection->setFetchMode(\PDO::FETCH_OBJ);
		}
	}

	public function select($select) {
		$this->connect();

		return new Db\Select($this, $select);
	}

	public function insert($set) {
		$this->connect();

		return new Db\Insert($this, $set);
	}

	public function update($set) {
		$this->connect();

		return new Db\Update($this, $set);
	}

	public function delete($where) {
		$this->connect();

		return new Db\Delete($this, $where);
	}
}