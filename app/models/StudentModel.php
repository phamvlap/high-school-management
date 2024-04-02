<?php

namespace App\models;

use PDO;
use App\db\PDOFactory;
use PDOException;


class StudentModel
{
	private PDO $pdo;

	public function __construct()
	{
		$this->pdo = PDOFactory::connect();
	}
	public function getCount(array $filter): int
	{
		$preparedStmt = 'select get_total_students(:full_name, :address, :class_id, :academic_year)';
		$statement = $this->pdo->prepare($preparedStmt);
		$statement->bindParam(':full_name', $filter['full_name'], PDO::PARAM_STR);
		$statement->bindParam(':address', $filter['address'], PDO::PARAM_STR);
		$statement->bindParam(':class_id', $filter['class_id'], PDO::PARAM_INT);
		$statement->bindParam(':academic_year', $filter['academic_year'], PDO::PARAM_STR);
		$statement->execute();
		return $statement->fetchColumn();
	}
	public function getAll(int $limit, int $offset): array
	{
		$preparedStmt = 'call get_all_students(null, null, null, null, 0, :limit, :offset)';
		$statement = $this->pdo->prepare($preparedStmt);
		$statement->bindParam(':limit', $limit, PDO::PARAM_INT);
		$statement->bindParam(':offset', $offset, PDO::PARAM_INT);
		$statement->execute();
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}
	public function getByFilter(array $filter, int $limit, int $offset): array
	{
		$preparedStmt = 'call get_all_students(:full_name, :address, :class_id, :academic_year, :is_order_by_name, :limit, :offset)';
		$statement = $this->pdo->prepare($preparedStmt);
		$statement->bindParam(':full_name', $filter['full_name'], PDO::PARAM_STR);
		$statement->bindParam(':address', $filter['address'], PDO::PARAM_STR);
		$statement->bindParam(':class_id', $filter['class_id'], PDO::PARAM_INT);
		$statement->bindParam(':academic_year', $filter['academic_year'], PDO::PARAM_STR);
		$statement->bindParam(':is_order_by_name', $filter['is_order_by_name'], PDO::PARAM_INT);
		$statement->bindParam(':limit', $limit, PDO::PARAM_INT);
		$statement->bindParam(':offset', $offset, PDO::PARAM_INT);
		$statement->execute();
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}


	public function store(array $data): void
	{
		$preparedStmt = 'call add_student(:student_id, :full_name, :date_of_birth, :address, :parent_phone_number, :class_id);';
		$statement = $this->pdo->prepare($preparedStmt);
		$statement->bindParam(':student_id', $data['student_id'], PDO::PARAM_INT);
		$statement->bindParam(':full_name', $data['full_name'], PDO::PARAM_STR);
		$statement->bindParam(':date_of_birth', $data['date_of_birth'], PDO::PARAM_STR);
		$statement->bindParam(':address', $data['address'], PDO::PARAM_STR);
		$statement->bindParam(':parent_phone_number', $data['parent_phone_number'], PDO::PARAM_STR);
		$statement->bindParam(':class_id', $data['class_id'], PDO::PARAM_INT);
		$statement->execute();
	}
	public function delete(int $student_id): void
	{
		$preparedStmt = 'call delete_student(:student_id)';
		$statement = $this->pdo->prepare($preparedStmt);
		$statement->bindParam(':student_id', $student_id, PDO::PARAM_INT);
		$statement->execute();
	}
}
