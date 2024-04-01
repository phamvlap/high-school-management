<?php

namespace App\models;

use PDO;
use App\db\PDOFactory;
use PDOException;


class ClassModel {
	private PDO $pdo;

	public function __construct() {
		$this -> pdo = PDOFactory::connect();
	}

	public function getPDO(): PDO {
		return $this->pdo;
	}
	public function getAll(): array {
			$preparedStmt = "call get_all_classes(null, null, null, 1);";
			$statement = $this->pdo->prepare($preparedStmt);
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
	public function getClassID(array $data): int {
		$preparedStmt = 'select get_classId(:class_name, :academic_year)';
		$statement = $this->pdo->prepare($preparedStmt);
		$statement->bindParam(':class_name', $data['class_name'], PDO::PARAM_STR);
		$statement->bindParam(':academic_year', $data['academic_year'], PDO::PARAM_STR);
		$statement->execute();
		return $statement->fetchColumn();
	}

	public function getByFilter(array $filter): array {
			$preparedStmt = 'call get_all_classes(:class_name, :grade, :academic_year, :is_order_by_class_id)';
			$statement = $this->pdo->prepare($preparedStmt);
			$statement->bindParam(':class_name', $filter['class_name'], PDO::PARAM_STR);
			$statement->bindParam(':grade', $filter['grade'], PDO::PARAM_STR);
			$statement->bindParam(':academic_year', $filter['academic_year)'], PDO::PARAM_STR);
			$statement->bindParam(':is_order_by_class_id', $filter['is_order_by_class_id'], PDO::PARAM_INT);
			$statement->execute();
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		
	}


	public function store(array $data): void {
		$preparedStmt = 'call add_class(-1, :class_name, :academic_year)';
		$statement = $this->pdo->prepare($preparedStmt);
		$statement->bindParam(':class_name', $data['class_name'], PDO::PARAM_STR);
		$statement->bindParam(':academic_year', $data['academic_year'], PDO::PARAM_STR);
		$statement->execute();
	}

	public function update(array $data): void {
		try{
			$preparedStmt = 'call add_class(:class_id, :class_name, :academic_year)';
			$statement = $this->pdo->prepare($preparedStmt);
			$statement->bindParam(':class_id', $data['class_id'], PDO::PARAM_INT);
			$statement->bindParam(':class_name', $data['class_name'], PDO::PARAM_STR);
			$statement->bindParam(':academic_year', $data['academic_year'], PDO::PARAM_STR);
			$statement->execute();
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function delete(int $class_id): void {
		$preparedStmt = 'call delete_class(:class_id)';
		$statement = $this->pdo->prepare($preparedStmt);
		$statement->bindParam(':class_id', $class_id, PDO::PARAM_INT);
		$statement->execute();
	}
	public function count(): int
    {
            $statement = $this->pdo->prepare("select count(*) from classes");
            $statement->execute();
            return $statement->fetchColumn();
    }
}
