<?php

namespace App\models;

use PDO;

use App\db\PDOFactory;
use PDOException;

class ClassModel  {
	private PDO $pdo;

	public function __construct() {
        $this->pdo = PDOFactory::connect();
	}
    pupblic function getAll(): array {
        try {
            $pdo = PDOFactory::connect();
            $preparedStmt = "call get_all_mark(null,null,null)";
            $statement = $pdo->prepare($preparedStmt);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

   
}