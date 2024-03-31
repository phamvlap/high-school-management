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
    public function getById(int $subject_id): array {
        try {
            $pdo = PDOFactory::connect();
            $preparedStmt = "call get_subject_by_id(:subject_id)";
            $statement = $pdo->prepare($preparedStmt);
            $statement->bindParam(':subject_id', $id, PDO::PARAM_INT);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
   
    public function store( array $data): void {
        try {
            $pdo = PDOFactory::connect();
            $preparedStmt = "call add_subject(:subject_id, :subject_name, :grade)";
            $statement = $pdo->prepare($preparedStmt);
            $statement->bindParam(':subject_id', $data['subject_id'], PDO::PARAM_STR);
            $statement->bindParam(':subject_name', $data['subject_name'], PDO::PARAM_STR);
            $statement->bindParam(':grade', $data['grade'], PDO::PARAM_STR);
            $statement->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    public function delete(int $subject_id): void {
        $preparedStmt = "call delete_subject(:subject_id)";
        $statement = $this->pdo->prepare($preparedStmt);
        $statement->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
        $statement->execute();
        
    }
    public function count(): int
    {
        try {
            $statement = $this->pdo->prepare("select count(*) from subjects");
            $statement->execute();
            return $statement->fetchColumn();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
 }
