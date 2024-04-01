<?php

namespace App\models;

use App\db\PDOFactory;
use PDO;
use PDOException;

class TeacherModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = PDOFactory::connect();
    }

    public function getAll(int $limit, int $offset): array
    {
        $preparedStmt = 'call get_all_teachers(null, null, null, :limit, :offset)';
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
        $preparedStmt = 'call get_all_teachers(:full_name, :address, :is_sort_by_name, :limit, :offset)';
        try {
            $statement = $this->pdo->prepare($preparedStmt);
            $statement->bindParam(':full_name', $filter['full_name'], PDO::PARAM_STR);
            $statement->bindParam(':address', $filter['address'], PDO::PARAM_STR);
            $statement->bindParam(':is_sort_by_name', $filter['is_sort_by_name'], PDO::PARAM_INT);
            $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
            $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getById(int $id): array
    {
        try {
            $statement = $this->pdo->prepare("call get_teacher_by_id(:teacher_id)");
            $statement->bindParam(':teacher_id', $id, PDO::PARAM_INT);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function store(array $data): void
    {
        try {
            $statement = $this->pdo->prepare("call add_teacher(:teacher_id, :full_name, :date_of_birth, :address, :phone_number)");
            $statement->bindParam(':teacher_id', $data['teacher_id'], PDO::PARAM_INT);
            $statement->bindParam(':full_name', $data['full_name'], PDO::PARAM_STR);
            $statement->bindParam(':date_of_birth', $data['date_of_birth'], PDO::PARAM_STR);
            $statement->bindParam(':address', $data['address'], PDO::PARAM_STR);
            $statement->bindParam(':phone_number', $data['phone_number'], PDO::PARAM_STR);
            $statement->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function delete(int $id): void
    {
        try {
            $statement = $this->pdo->prepare("call delete_teacher(:teacher_id)");
            $statement->bindParam(':teacher_id', $id, PDO::PARAM_INT);
            $statement->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }


    public function getCount(array $filter): int
    {
        $preparedStmt = 'call get_total_teachers(:full_name, :address);';
        try {
            $statement = $this->pdo->prepare($preparedStmt);
            $statement->bindParam(':full_name', $filter['full_name'], PDO::PARAM_STR);
            $statement->bindParam(':address', $filter['address'], PDO::PARAM_STR);
            $statement->execute();
            return $statement->fetchColumn();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
