<?php

namespace App\models;

use PDO;
use App\db\PDOFactory;
use PDOException;


class StudentModel {
	private PDO $pdo;

	public function __construct() {
		$this -> pdo = PDOFactory::connect();
	}
	public function getCount(array $filter): int {
		try {
			$stmt = $this -> pdo -> prepare('select get_total_students(:address, :class_id, :academic_year);');
			$stmt -> bindParam(':address', $filter['address'], PDO::PARAM_STR);
			$stmt -> bindParam(':class_id', $filter['class_id'], PDO::PARAM_INT);
			$stmt -> bindParam(':academic_year', $filter['academic_year'], PDO::PARAM_STR);
            $stmt -> execute();
            return $stmt -> fetchColumn();
		} catch(PDOException $e) {
			echo $e -> getMessage();
		}
}
	public function getAll(): array {
			$preparedStmt = "call get_all_students(null, null, null, 0);";
			$statement = $this->pdo->prepare($preparedStmt);
			$statement->execute();
			return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	// public function getById(int $student_id): array {
	// 	$preparedStmt = 'call get_student_by_id(:student_id)';
	// 	$statement = $this->pdo->prepare($preparedStmt);
	// 	$statement->bindParam(':student_id', $student_id, PDO::PARAM_INT);
	// 	$statement->execute();
	// 	return $statement->fetchAll(PDO::FETCH_ASSOC);
	// }
	public function getByFilter(array $filter, int $limit, int $offset): array {
			$preparedStmt = 'call get_all_students(:address, :class_id, :academic_year, :is_sort_by_name_desc)';
			$statement = $this->pdo->prepare($preparedStmt);
			$statement->bindParam(':address', $filter['address'], PDO::PARAM_STR);
			$statement->bindParam(':class_id', $filter['class_id'], PDO::PARAM_INT);
			$statement->bindParam(':academic_year', $filter['academic_year'], PDO::PARAM_STR);
			$statement->bindParam(':is_sort_by_name_desc', $filter['is_sort_by_name_desc'], PDO::PARAM_INT);
			$statement->execute();
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		
	}

	public function store(array $data): void {
		$preparedStmt = 'call add_student(:student_id, :full_name, :date_of_birth, :address, :parent_phone_number, :class_id, :academic_year),';
		$statement = $this->pdo->prepare($preparedStmt);
		$statement->bindParam(':student_id', $data['student_id'], PDO::PARAM_INT);
		$statement->bindParam(':full_name', $data['full_name'], PDO::PARAM_STR);
		$statement->bindParam(':date_of_birth', $data['date_of_birth'], PDO::PARAM_STR);
		$statement->bindParam(':address', $data['address'], PDO::PARAM_STR);
		$statement->bindParam(':parent_phone_number', $data['parent_phone_number'], PDO::PARAM_STR);
		$statement->bindParam(':class_id', $data['class_id'], PDO::PARAM_INT);
		$statement->bindParam(':academic_year', $data['academic_year'], PDO::PARAM_STR);
		$statement->execute();
	}

	public function update(array $data): void {
			echo 'update';
	}
	public function delete(int $student_id): void {
		$preparedStmt = 'call delete_student(:student_id)';
		$statement = $this->pdo->prepare($preparedStmt);
		$statement->bindParam(':student_id', $student_id, PDO::PARAM_INT);
		$statement->execute();
	}
	public function count(): int
    {
            $statement = $this->pdo->prepare("select count(*) from students;");
            $statement->execute();
            return $statement->fetchColumn();
    }
	public function updateClassID(int $class_id, int $new_class_id): void {
		$preparedStmt = 'call update_student_class_id(:class_id, :new_class_id)';
		$statement = $this->pdo->prepare($preparedStmt);
		$statement->bindParam(':class_id', $class_id, PDO::PARAM_INT);
		$statement->bindParam(':new_class_id', $new_class_id, PDO::PARAM_INT);
		$statement->execute();
	}
}
