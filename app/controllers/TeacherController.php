<?php

namespace App\controllers;

use App\models\TeacherModel;
use App\utils\Validator;

class TeacherController
{
	private $rules;
	public function __construct()
	{
		$this->rules = [
			'full_name' =>
			[
				'isRequired' => 'Tên không được để trống',
				'isString' => 'Tên phải là chuỗi',
				'maxLength:255', 'message' => 'Tên không được quá 255 ký tự'
			],
			'date_of_birth' =>
			[
				'isRequired' => 'Ngày sinh không được để trống',
				'isDate' => 'Ngày sinh không hợp lệ'
			],
			'address' =>
			[
				'isRequired' => 'Địa chỉ không được để trống',
				'isString' => 'Địa chỉ phải là chuỗi',
				'maxLength:255', 'message' => 'Địa chỉ không được quá 255 ký tự'
			],
			'phone_number' =>
			[
				'isRequired' => 'Số điện thoại không được để trống',
				'isPhoneNumber' => 'Số điện thoại không hợp lệ'
			]
		];
	}

	public function index()
	{
		$teacherModel = new TeacherModel();
		$teachers = $teacherModel->getAll();
		render_view('teachers/index', [
			'teachers' => $teachers
		]);
	}
	public function create()
	{
		echo 'teacher create';
	}
	public function store()
	{
		$teacherModel = new TeacherModel();

		// Validation
		$data = [];
		$data['full_name'] = $_POST['full_name'] ?? '';
		$data['date_of_birth'] = $_POST['date_of_birth'] ?? '';
		$data['address'] = $_POST['address'] ?? '';
		$data['phone_number'] = $_POST['phone_number'] ?? '';

		$errors = Validator::validate($data, $this->rules);
		if ($errors) {
			$this->index();
			return;
		}

		$teacherModel->store($data);
		$this->index();
	}
	public function edit()
	{
		echo 'teacher edit';
	}
	public function update()
	{
		echo 'teacher update';
	}
	public function delete()
	{
		echo 'teacher delete';
	}
}
