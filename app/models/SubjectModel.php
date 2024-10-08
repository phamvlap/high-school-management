<?php

namespace App\models;

use PDO;
use App\db\PDOFactory;
use PDOException;

class SubjectModel
{
    private PDO $pdo;
    public function __construct()
    {
        $this->pdo = PDOFactory::connect();
    }
    public function getById(int $subject_id): array
    {
        $pdo = PDOFactory::connect();
        $preparedStmt = "call get_subject_by_id(:subject_id)";
        $statement = $pdo->prepare($preparedStmt);
        $statement->bindParam(':subject_id', $id, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function store(array $data): void
    {
        $pdo = PDOFactory::connect();
        $preparedStmt = "call add_subject(:subject_id, :subject_name, :grade)";
        $statement = $pdo->prepare($preparedStmt);
        $statement->bindParam(':subject_id', $data['subject_id'], PDO::PARAM_STR);
        $statement->bindParam(':subject_name', $data['subject_name'], PDO::PARAM_STR);
        $statement->bindParam(':grade', $data['grade'], PDO::PARAM_STR);
        $statement->execute();
    }
    public function getByFilter(array $filter, int $limit, int $offset): array
    {
        $preparedStmt = 'call get_all_subjects(:subject_name, :grade, :limit, :offset)';
        $statement = $this->pdo->prepare($preparedStmt);
        $statement->bindParam(':subject_name', $filter['subject_name'], PDO::PARAM_STR);
        $statement->bindParam(':grade', $filter['grade'], PDO::PARAM_INT);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    public function delete(int $subject_id): void
    {
        $preparedStmt = "call delete_subject(:subject_id)";
        $statement = $this->pdo->prepare($preparedStmt);
        $statement->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
        $statement->execute();
    }
    public function getCount($filter): int
    {
        $preparedStmt = 'select get_total_subjects(:subject_name,:grade)';
        $statement = $this->pdo->prepare($preparedStmt);
        $statement->bindParam(':subject_name', $filter['subject_name'], PDO::PARAM_INT);
        $statement->bindParam(':grade', $filter['grade'], PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchColumn();
    }
}
