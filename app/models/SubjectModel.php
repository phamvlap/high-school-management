<?php
namespace App\models;
use PDO;
use App\db\PDOFactory;
use PDOException;
 class SubjectModel{
    private PDO $pdo;
    public function __construct() {
        $this->pdo = PDOFactory::connect();
    }
    protected function getPDO(): PDO {
        return $this->pdo;
    }
    public function getAll(): array {
        try {
            $pdo = PDOFactory::connect();
            $preparedStmt = "call get_all_subject(null,null,null)";
            $statement = $pdo->prepare($preparedStmt);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    public function getById(int $id): array {
        try {
            $pdo = PDOFactory::connect();
            $preparedStmt = "call get_subject_by_id(:id)";
            $statement = $pdo->prepare($preparedStmt);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    public function store(array $data): void {
        try {
            $pdo = PDOFactory::connect();
            $preparedStmt = "call add_subject(-1, :name, :grade)";
            $statement = $pdo->prepare($preparedStmt);
            $statement->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $statement->bindParam(':grade', $data['grade'], PDO::PARAM_STR);
            $statement->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    public function update( array $data): void {
        try {
            $pdo = PDOFactory::connect();
            $preparedStmt = "call update_subject(:id_subject, :name, :grade)";
            $statement = $pdo->prepare($preparedStmt);
            $statement->bindParam(':id_subject', $data['id_subject'], PDO::PARAM_STR);
            $statement->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $statement->bindParam(':grade', $data['grade'], PDO::PARAM_STR);
            $statement->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    public function delete(int $id): void {
        $preparedStmt = "call delete_subject(:id)";
        $statement = $this->pdo->prepare($preparedStmt);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    }
 }
