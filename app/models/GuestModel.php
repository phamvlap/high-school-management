<?php

namespace App\models;

use App\db\PDOFactory;
use PDO;

class GuestModel {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = PDOFactory::connect();
    }

    public function getMarkTableByParentTelephone(string $telephone): array {
        $statement = $this->pdo->prepare("call get_mark_table_by_parent_telephone(:telephone)");
        $statement->bindParam(':telephone', $telephone, PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMarkTableByStudentID(string $student_id): array {
        $statement = $this->pdo->prepare("call get_mark_table_by_student_id(:student_id)");
        $statement->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
