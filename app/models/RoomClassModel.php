<?php

namespace App\models;

use PDO;
use PDOException;

use App\db\PDOFactory;

class RoomClassModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = PDOFactory::connect();
    }

    public function getAll(int $limit, int $offset): array
    {
        $preparedStmt = 'call get_all_room_class(null, null, null, null, null, -1, :limit, :offset)';
        $statement = $this->pdo->prepare($preparedStmt);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByFilter(array $filter, int $limit, int $offset): array
    {
        $preparedStmt = 'call get_all_room_class(:room_id, :class_id, :grade, :semester, :academic_year, :is_sort_by_classname, :limit, :offset)';
        $statement = $this->pdo->prepare($preparedStmt);
        $statement->bindParam(':room_id', $filter['room_id']);
        $statement->bindParam(':class_id', $filter['class_id']);
        $statement->bindParam(':grade', $filter['grade']);
        $statement->bindParam(':semester', $filter['semester']);
        $statement->bindParam(':academic_year', $filter['academic_year'], PDO::PARAM_STR);
        $statement->bindParam(':is_sort_by_classname', $filter['is_sort_by_classname'], PDO::PARAM_INT);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCount(array $filter): int
    {
        $preparedStmt = 'select get_total_room_class(:room_id, :class_id, :grade, :semester, :academic_year)';
        $statement = $this->pdo->prepare($preparedStmt);
        $statement->bindParam(':room_id', $filter['room_id']);
        $statement->bindParam(':class_id', $filter['class_id']);
        $statement->bindParam(':grade', $filter['grade']);
        $statement->bindParam(':semester', $filter['semester']);
        $statement->bindParam(':academic_year', $filter['academic_year'], PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchColumn();
    }

    public function store(array $data): void
    {
        $preparedStmt = "call add_room_class(:room_id, :class_id, :semester);";
        $statement = $this->pdo->prepare($preparedStmt);
        $statement->bindParam(':room_id', $data['room_id'], PDO::PARAM_INT);
        $statement->bindParam(':class_id', $data['class_id'], PDO::PARAM_INT);
        $statement->bindParam(':semester', $data['semester'], PDO::PARAM_INT);
        $statement->execute();
    }

    public function delete(array $data): void
    {
        $preparedStmt = 'call delete_room_class(:class_id, :semester)';
        $statement = $this->pdo->prepare($preparedStmt);
        $statement->bindParam(':class_id', $data['class_id'], PDO::PARAM_INT);
        $statement->bindParam(':semester', $data['semester'], PDO::PARAM_INT);
        $statement->execute();
    }
}
