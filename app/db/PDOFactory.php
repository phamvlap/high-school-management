<?php

namespace App\db;

use PDO;
use PDOException;

class PDOFactory {
	static public function connect(): PDO {
		$host = $_ENV['DB_HOST'];
		$dbname = $_ENV['DB_NAME'];
		$user = $_ENV['DB_USER'];
		$password = $_ENV['DB_PASS'];

		$dsn = "mysql:host=$host;port=3306;dbname=$dbname;charset=utf8";
		try {
			return new PDO($dsn, $user, $password);
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
}