<?php

namespace App\models;

use PDO;
use PDOException;
use App\db\PDOFactory;

class RoomModel
{
	private PDO $pdo;

	public function __construct()
	{
		$this->pdo = PDOFactory::connect();
	}

	public function getCount(array $filter): int
	{
		$preparedStmt = 'select get_total_rooms(:room_number, :min_capacity, :max_capacity);';
		$statement = $this->pdo->prepare($preparedStmt);
		$statement->bindParam(':room_number', $filter['room_number'], PDO::PARAM_STR);
		$statement->bindParam(':min_capacity', $filter['min_capacity'], PDO::PARAM_INT);
		$statement->bindParam(':max_capacity', $filter['max_capacity'], PDO::PARAM_INT);
		$statement->execute();
		return $statement->fetchColumn();
	}

	public function getAll(int $limit, int $offset): array
	{
		$preparedStmt = 'call get_all_rooms(null, null, null, null, :limit, :offset)';
		$statement = $this->pdo->prepare($preparedStmt);
		$statement->bindParam(':limit', $limit, PDO::PARAM_INT);
		$statement->bindParam(':offset', $offset, PDO::PARAM_INT);
		$statement->execute();
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getByFilter(array $filter, int $limit, int $offset): array
	{
		$preparedStmt = 'call get_all_rooms(:room_number, :min_capacity, :max_capacity, :is_sort_by_capacity, :limit, :offset)';
		$statement = $this->pdo->prepare($preparedStmt);
		$statement->bindParam(':room_number', $filter['room_number'], PDO::PARAM_STR);
		$statement->bindParam(':min_capacity', $filter['min_capacity'], PDO::PARAM_INT);
		$statement->bindParam(':max_capacity', $filter['max_capacity'], PDO::PARAM_INT);
		$statement->bindParam(':is_sort_by_capacity', $filter['is_sort_by_capacity'], PDO::PARAM_INT);
		$statement->bindParam(':limit', $limit, PDO::PARAM_INT);
		$statement->bindParam(':offset', $offset, PDO::PARAM_INT);
		$statement->execute();
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getById(int $id): array
	{
		$preparedStmt = 'call get_room_by_id(:room_id)';
		$statement = $this->pdo->prepare($preparedStmt);
		$statement->bindParam(':room_id', $id, PDO::PARAM_INT);
		$statement->execute();
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function store(array $data): void
	{
		$preparedStmt = 'call add_room(:room_id, :room_number, :maximum_capacity)';
		$statement = $this->pdo->prepare($preparedStmt);
		$statement->bindParam(':room_id', $data['room_id'], PDO::PARAM_INT);
		$statement->bindParam(':room_number', $data['room_number'], PDO::PARAM_STR);
		$statement->bindParam(':maximum_capacity', $data['maximum_capacity'], PDO::PARAM_INT);
		$statement->execute();
	}

	public function delete(int $id): void
	{
		$preparedStmt = 'call delete_room(:room_id)';
		$statement = $this->pdo->prepare($preparedStmt);
		$statement->bindParam(':room_id', $id, PDO::PARAM_INT);
		$statement->execute();
	}
}
