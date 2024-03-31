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

    public function getAll(): array
    {
        try {
            $statement = $this->pdo->prepare("call get_all_teachers(null, null, null, null, null)");
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

    public function count(): int
    {
        try {
            $statement = $this->pdo->prepare("select count(*) from teachers");
            $statement->execute();
            return $statement->fetchColumn();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
