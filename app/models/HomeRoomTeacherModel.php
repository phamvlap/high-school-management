<?php

namespace App\models;

use PDO;
use PDOException;

use App\db\PDOFactory;

class HomeRoomTeacherModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = PDOFactory::connect();
    }

    public function store(array $data): void
    {
        $preparedStmt = "call add_homeroom_teacher(:teacher_id, :class_id);";
        $statement = $this->pdo->prepare($preparedStmt);
        $statement->bindParam(':teacher_id', $data['teacher_id'], PDO::PARAM_INT);
        $statement->bindParam(':class_id', $data['class_id'], PDO::PARAM_INT);
        $statement->execute();
    }

    public function delete(array $data): void
    {
        $preparedStmt = 'call delete_homeroom_teacher(:teacher_id, :class_id)';
        $statement = $this->pdo->prepare($preparedStmt);
        $statement->bindParam(':teacher_id', $data['teacher_id'], PDO::PARAM_INT);
        $statement->bindParam(':class_id', $data['class_id'], PDO::PARAM_INT);
        $statement->execute();
    }
}
