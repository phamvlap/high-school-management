<?php

namespace App\models;

use App\db\PDOFactory;
use PDO;
use PDOException;

class AccountModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = PDOFactory::connect();
    }

    public function getByUsername(string $username)
    {
        try {
            $statement = $this->pdo->prepare("call get_account_by_username(:username)");
            $statement->bindParam(':username', $username, PDO::PARAM_STR);
            $statement->execute();
            return $statement->fetch();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
