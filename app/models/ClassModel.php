<?php

namespace App\models;

use PDO;
use App\db\PDOFactory;
use PDOException;

class ClassModel
{
	private PDO $pdo;

	public function __construct()
	{
		$this->pdo = PDOFactory::connect();
	}

	public function getAll(int $limit, int $offset): array
	{
		$preparedStmt = 'call get_all_classes(null, null, null, null, :limit, :offset)';
		try {
			$statement = $this->pdo->prepare($preparedStmt);
			$statement->bindParam(':limit', $limit, PDO::PARAM_INT);
			$statement->bindParam(':offset', $offset, PDO::PARAM_INT);
			$statement->execute();
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function getByFilter(array $filter, int $limit, int $offset): array
	{
		$preparedStmt = 'call get_all_classes(:class_name, :grade, :academic_year, :limit, :offset)';
		try {
			$statement = $this->pdo->prepare($preparedStmt);
			$statement->bindParam(':class_name', $filter['class_name'], PDO::PARAM_STR);
			$statement->bindParam(':grade', $filter['grade'], PDO::PARAM_STR);
			$statement->bindParam(':academic_year', $filter['academic_year'], PDO::PARAM_STR);
			$statement->bindParam(':limit', $limit, PDO::PARAM_INT);
			$statement->bindParam(':offset', $offset, PDO::PARAM_INT);
			$statement->execute();
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function getCount(array $filter): int
	{
		$preparedStmt = 'select get_total_classes(:class_name, :grade, :academic_year)';
		try {
			$statement = $this->pdo->prepare($preparedStmt);
			$statement->bindParam(':class_name', $filter['class_name'], PDO::PARAM_STR);
			$statement->bindParam(':grade', $filter['grade'], PDO::PARAM_STR);
			$statement->bindParam(':academic_year', $filter['academic_year'], PDO::PARAM_STR);
			$statement->execute();
			return $statement->fetchColumn();
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function getClassId(): int
	{
		$preparedStmt = 'select get_inserted_id()';
		$statement = $this->pdo->prepare($preparedStmt);
		$statement->execute();
		return $statement->fetchColumn();
	}

	public function store(array $data): void
	{
		try {
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

	public function delete(int $class_id): void
	{
		$preparedStmt = 'call delete_class(:class_id)';
		$statement = $this->pdo->prepare($preparedStmt);
		$statement->bindParam(':class_id', $class_id, PDO::PARAM_INT);
		$statement->execute();
	}
}
