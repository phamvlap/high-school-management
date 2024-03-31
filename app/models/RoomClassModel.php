<?php

namespace App\models;

use PDO;
use PDOException;

use App\db\PDOFactory;

class RoomClassModel {
    private $pdo;

    public function __construct() {
        $this -> pdo = PDOFactory::connect();
    }

    public function getPDO(): PDO {
        return $this->pdo;
    }

    public function store(array $data): void {
        try {
            $preparedStmt = "call add_room_class(:room_id, :class_id, :semester);";
            $statement = $this->pdo->prepare($preparedStmt);
            $statement->bindParam(':room_id', $data['room_id'], PDO::PARAM_INT);
            $statement->bindParam(':class_id', $data['class_id'], PDO::PARAM_INT);
            $statement->bindParam(':semester', $data['semester'], PDO::PARAM_INT);
            $statement->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function delete(array $data): void {
        try {
            $preparedStmt = 'call delete_room_class(:room_id, :class_id, :semester)';
            $statement = $this->pdo->prepare($preparedStmt);
            $statement->bindParam(':room_id', $id, PDO::PARAM_INT);
            $statement->bindParam(':class_id', $data['class_id'], PDO::PARAM_INT);
            $statement->bindParam(':semester', $data['semester'], PDO::PARAM_INT);
            $statement->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function update(array $data): void {
        try {
            $preparedStmt = 'call update_room_class(:room_id, :class_id, :semester, :new_room_id, :new_semester)';
            $statement = $this->pdo->prepare($preparedStmt);
            $statement->bindParam(':room_id', $data['room_id'], PDO::PARAM_INT);
            $statement->bindParam(':class_id', $data['class_id'], PDO::PARAM_INT);
            $statement->bindParam(':semester', $data['semester'], PDO::PARAM_INT);
            $statement->bindParam(':new_room_id', $data['new_room_id'], PDO::PARAM_INT);
            $statement->bindParam(':new_semester', $data['new_semester'], PDO::PARAM_INT);
            $statement->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}