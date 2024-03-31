<?php

namespace App\controllers;

use App\models\{ClassModel, BaseModel};
use App\db\PDOFactory;
use PDO;
use PDOException;

class ClassController
{
	public function index()
	{
		try {
			$pdo = PDOFactory::connect();
			// print_r($pdo);
			echo 'hi world';
			$classModel = new ClassModel();
			// $classes = $classModel->getAll();
			// var_dump($classes);
			// echo 'hi world';

			// $preparedStmt = "call get_all_classes(:k1, :k2, :k3, :k4);";
			$preparedStmt = "select * from classes";
			$statement = $pdo->prepare($preparedStmt);
			// $statement->bindParam(':k1', null, PDO::PARAM_INT);
			// $statement->bindParam(':k2', null, PDO::PARAM_INT);
			// $statement->bindParam(':k3', null, PDO::PARAM_INT);
			// $statement->bindParam(':k4', null, PDO::PARAM_INT);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			var_dump($result);
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
	public function create()
	{
		echo 'class create';
	}
	public function store()
	{
		echo 'class store';
	}
	public function edit()
	{
		echo 'class edit';
	}
	public function update()
	{
		echo 'class update';
	}
	public function delete()
	{
		echo 'class delete';
	}
}
