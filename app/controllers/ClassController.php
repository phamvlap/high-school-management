<?php

namespace App\controllers;

use App\models\{ClassModel};
use App\db\PDOFactory;
use PDO;
use PDOException;
use App\utils\{Validator, Helper};

class ClassController
{
	public function index()
	{
		try{
			$ClassModel = new ClassModel();
		$classes = $ClassModel->getAll();
		Helper::renderPage('/classes/index.php', [
			'classes' => $classes
		]);
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}
	public function create()
	{
		echo 'class create';
	}
	public function store()
	{
		try{
			$ClassModel = new ClassModel();
			$data = [];
			$data['class_id'] = $_POST['class_id'] ?? '-1';
			$data['class_name'] = $_POST['class_name'] ?? '';
			$data['academic_year'] = $_POST['academic_year'] ?? '';
			$data['']
			
		}
		
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
