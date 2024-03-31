<?php
namespace App\models;
use PDO;
use App\db\PDOFactory;
use PDOException;
class Teaching{
    private PDO $pdo;
    public function __construct() {
        $this->pdo = PDOFactory::connect();
    }
    public function add_teaching($data){
        try {
            $pdo = PDOFactory::connect();
            $preparedStmt = "call add_teaching(:teacher_id, :subject_id, :academic_year)";
            $statement = $pdo->prepare($preparedStmt);
            $statement->bindParam(':class_id', $data['teacher_id'], PDO::PARAM_INT);
            $statement->bindParam(':subject_id', $data['subject_id'], PDO::PARAM_INT);
            $statement->bindParam(':teacher_id', $data['academic_year'], PDO::PARAM_INT);
            $statement->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    public function delete_teaching($data){
        $preparedStmt = "call delete_teaching(:teacher_id, :subject_id, :academic_year)";
        $statement = $this->pdo->prepare($preparedStmt);
        $statement->bindParam(':teacher_id', $data['teacher_id'], PDO::PARAM_INT);
        $statement->bindParam(':subject_id', $data['subject_id'], PDO::PARAM_INT);
        $statement->bindParam(':academic_year', $data['academic_year'], PDO::PARAM_INT);
        $statement->execute();
        
    }
    public function update_teaching($data){
        $preparedStmt = "call update_teaching(:teacher_id, :subject_id, :academic_year)";
        $statement = $this->pdo->prepare($preparedStmt);
        $statement->bindParam(':teacher_id', $data['teacher_id'], PDO::PARAM_INT);
        $statement->bindParam(':subject_id', $data['subject_id'], PDO::PARAM_INT);
        $statement->bindParam(':academic_year', $data['academic_year'], PDO::PARAM_INT);
        $statement->execute();
    }
    public function count(): int
    {
        try {
            $pdo = PDOFactory::connect();
            $preparedStmt = "call count_teaching()";
            $statement = $pdo->prepare($preparedStmt);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC)['count'];
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

}
    