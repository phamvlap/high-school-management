<?php

namespace App\models;

use PDO;
use PDOException;
use App\db\PDOFactory;

class AccountModel {
	private PDO $pdo;

	public function __construct(){
		$this->pdo = PDOFactory::connect();
	}

	public function getCount(array $filter): int {
		$preparedStmt = 'select get_total_accounts(:username, :type)';
		try {
			$statement = $this->pdo->prepare($preparedStmt);
			$statement->bindParam(':username', $filter['username'], PDO::PARAM_STR);
			$statement->bindParam(':type', $filter['type'], PDO::PARAM_INT);
			$statement->execute();
			return $statement->fetchColumn();
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function getAll( int $limit, $offset): array {
		try {
            $statement = $this->pdo->prepare("call get_all_accounts(null, null, :limit, :offset)");
			$statement->bindParam(':limit', $limit, PDO::PARAM_INT);
			$statement->bindParam(':offset', $offset, PDO::PARAM_INT);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
	}

	public function getByFilter(array $filter, int $limit, int $offset): array {
		$preparedStmt = 'call get_all_accounts(:username, :type, :limit, :offset)';
		try {
			$statement = $this->pdo->prepare($preparedStmt);
			$statement->bindParam(':username', $filter['username'], PDO::PARAM_STR);
			$statement->bindParam(':type', $filter['type'], PDO::PARAM_STR);
			$statement->bindParam(':limit', $limit, PDO::PARAM_INT);
			$statement->bindParam(':offset', $offset, PDO::PARAM_INT);
			$statement->execute();
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function getByUsername(string $username): array {
		$preparedStmt = 'call get_account_by_username(:username';
		try {
			$statement = $this->pdo->prepare($preparedStmt);
			$statement->bindParam(':username', $username, PDO::PARAM_STR);
			$statement->execute();
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function store(array $data): void {
		try {
			$statement = $this->pdo->prepare("call add_account(:username, :password, :type)");
			$statement->bindParam(':username', $data['username'], PDO::PARAM_STR);
            $statement->bindParam(':password', $data['password'], PDO::PARAM_STR);
            $statement->bindParam(':type', $data['type'], PDO::PARAM_STR);
            $statement->execute();
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function delete(string $username): void {
		$preparedStmt = 'call delete_account(:username)';
		try {
			$statement = $this->pdo->prepare($preparedStmt);
			$statement->bindParam(':username', $username, PDO::PARAM_STR);
			$statement->execute();
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
}