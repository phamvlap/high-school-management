<?php

namespace App\controllers;

use App\models\{SubjectModel, BaseModel};
use App\db\PDOFactory;
use PDO;
use PDOException;

class SubjectController
{
	public function index()
	{
        try {
            $subjectModel = new SubjectModel();
            $subjects = $subjectModel->getAll();
            require_once './../views/subject.php';
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
