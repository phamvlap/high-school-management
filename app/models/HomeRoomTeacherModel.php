<?php 

namespace App\models;

use PDO;
use PDOException;

use App\db\PDOFactory;

class HomeRoomTeacherModel {
    private PDO $pdo;
    
    public function __construct() {
        $this -> pdo = PDOFactory::connect();
    }
    
    public function getPDO(): PDO {
        return $this->pdo;
    }

    public function store(array $data): void {
        try {
            $preparedStmt = "call add_homeroom_teacher(:teacher_id, :class_id);";
            $statement = $this->pdo->prepare($preparedStmt);
            $statement->bindParam(':teacher_id', $data['teacher_id'], PDO::PARAM_INT);
            $statement->bindParam(':class_id', $data['class_id'], PDO::PARAM_INT);
            $statement->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function delete(array $data): void {
        try {
            $preparedStmt = 'call delete_homeroom_teacher(:teacher_id, :class_id)';
            $statement = $this->pdo->prepare($preparedStmt);
            $statement->bindParam(':teacher_id', $data['teacher_id'], PDO::PARAM_INT);
            $statement->bindParam(':class_id', $data['class_id'], PDO::PARAM_INT);
            $statement->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function update(array $data): void {
        try {
            $preparedStmt = 'call update_homeroom_teacher(:teacher_id, :class_id, :new_teacher_id)';
            $statement = $this->pdo->prepare($preparedStmt);
            $statement->bindParam(':teacher_id', $data['teacher_id'], PDO::PARAM_INT);
            $statement->bindParam(':class_id', $data['class_id'], PDO::PARAM_INT);
            $statement->bindParam(':new_teacher_id', $data['new_teacher_id'], PDO::PARAM_INT);
            $statement->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}