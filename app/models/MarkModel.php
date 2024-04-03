<?php

namespace App\models;

use PDO;

use App\db\PDOFactory;
use PDOException;

class MarkModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = PDOFactory::connect();
    }
    public function store(array $data): void
    {
        $pdo = PDOFactory::connect();
        $preparedStmt = "call add_mark( :student_id, :subject_id, :semester,:oral_score,:_15_minutes_score,:_1_period_score,:semester_score)";
        $statement = $pdo->prepare($preparedStmt);
        $statement->bindParam(':student_id', $data['student_id'], PDO::PARAM_STR);
        $statement->bindParam(':subject_id', $data['subject_id'], PDO::PARAM_STR);
        $statement->bindParam(':semester', $data['semester'], PDO::PARAM_STR);
        $statement->bindParam(':oral_score', $data['oral_score'], PDO::PARAM_STR);
        $statement->bindParam(':_15_minutes_score', $data['_15_minutes_score'], PDO::PARAM_STR);
        $statement->bindParam(':_1_period_score', $data['_1_period_score'], PDO::PARAM_STR);
        $statement->bindParam(':semester_score', $data['semester_score'], PDO::PARAM_STR);
        $statement->execute();
    }
    public function getByFilter(array $filter, int $limit, int $offset): array
    {
        $preparedStmt = 'call get_all_marks(:student_id, :subject_id, :semester, :limit, :offset)';
        $statement = $this->pdo->prepare($preparedStmt);
        $statement->bindParam(':student_id', $filter['student_id']);
        $statement->bindParam(':subject_id', $filter['subject_id']);
        $statement->bindParam(':semester', $filter['semester']);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    public function delete(int $student_id, int $subject_id, int $semester): void
    {
        $preparedStmt = "call delete_mark(:student_id,:subject_id,:semester)";
        $statement = $this->pdo->prepare($preparedStmt);
        $statement->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $statement->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
        $statement->bindParam(':semester', $semester, PDO::PARAM_INT);
        $statement->execute();
    }
    public function getCount($filter): int
    {
        $preparedStmt = 'select get_total_marks(:student_id, :subject_id, :semester)';
        $statement = $this->pdo->prepare($preparedStmt);
        $statement->bindParam(':student_id', $filter['student_id']);
        $statement->bindParam(':subject_id', $filter['subject_id']);
        $statement->bindParam(':semester', $filter['semester']);
        $statement->execute();
        return $statement->fetchColumn();
    }
}
