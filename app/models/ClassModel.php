<?php

namespace App\models;

use PDO;
use App\models\BaseModel;

class ClassModel extends BaseModel {
	private PDO $pdo;

	public function __construct() {
		parent::__construct();
		$this->pdo = parent::getPDO();
	}

	public function getAll(): array {
		$preparedStmt = "call get_all_classes(:class_name, :grade, :academic, :is_order_by_class_name);";
		$statement = $this->pdo->prepare($preparedStmt);
		$statement->bindParam(':class_name', null, PDO::PARAM_STR);
		$statement->bindParam(':grade', null, PDO::PARAM_STR);
		$statement->bindParam(':academic', null, PDO::PARAM_STR);
		$statement->bindParam(':is_order_by_class_name', null, PDO::PARAM_STR);
		$statement->execute();
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getById(int $id): array {
		$preparedStmt = 'call get_class_by_id(:id)';
		$statement = $this->pdo->prepare($preparedStmt);
		$statement->bindParam(':id', $id, PDO::PARAM_INT);
		$statement->execute();
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getByFilter(array $filter): array {
		$preparedStmt = 'call get_all_classes(:class_name, :grade, :academic, :is_order_by_class_name)';
		$statement = $this->pdo->prepare($preparedStmt);
		$statement->bindParam(':class_name', $filter['class_name'], PDO::PARAM_STR);
		$statement->bindParam(':grade', $filter['grade'], PDO::PARAM_STR);
		$statement->bindParam(':academic_year)', $filter['academic_year)'], PDO::PARAM_STR);
		$statement->bindParam(':is_order_by_class_name', $filter['is_order_by_class_name'], PDO::PARAM_STR);
		$statement->execute();
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function store(array $data): void {
		$preparedStmt = 'call add_class(:class_id, :class_name, :academic_year)';
		$stament = $this->pdo->prepare($preparedStmt);
		$stament->bindParam(':class_id', $data['class_id'], PDO::PARAM_INT);
		$stament->bindParam(':class_name', $data['class_name'], PDO::PARAM_STR);
		$stament->bindParam(':academic_year', $data['academic_year'], PDO::PARAM_STR);
		$statement->execute();
	}

	public function update(int $id, array $data): void {

	}

	public function delete(int $id): void {
		$preparedStmt = 'call delete_class(:id)';
		$statement = $this->pdo->prepare($preparedStmt);
		$statement->bindParam(':id', $id, PDO::PARAM_INT);
		$statement->execute();
	}
}
