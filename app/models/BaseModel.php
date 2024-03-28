<?php

namespace App\models;

use PDO;
use App\db\PDOFactory;

abstract class BaseModel {
	private PDO $pdo;

	protected function __construct() {
		$this->pdo = PDOFactory::connect();
	}

	protected function getPDO(): PDO {
		return $this->pdo;
	}

	abstract protected function getAll(): array {}

	abstract protected function getById(int $id): array {}

	abstract protected function store(array $data): void {}

	abstract protected function update(int $id, array $data): void {}

	abstract protected function delete(int $id): void {}
}