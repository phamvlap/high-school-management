<?php

namespace App\controllers;

use App\models\{ClassModel, RoomClassModel, HomeRoomTeacherModel};

use App\utils\{Validator, Helper};

class ClassController
{
	private $rules;
	public function __construct()
	{
		$this->rules = [
			'class_id' =>
			[
                'isRequired' => 'Mã lớp không được để trống'
            ],
			'class_name' =>
			[
				'isRequired' => 'Tên lớp không được để trống',
				'isString' => 'Tên phải là chuỗi',
				'maxLength:10' => 'Tên không được quá 10 ký tự'
			],
			'academic_year' =>
			[
				'isRequired' => 'Năm học không được để trống',
				'isString' => 'Năm học không hợp lệ',
				'maxLength:9', 'Năm học gồm 9 ký tự',
				'minLength:9' => 'Năm học gồm 9 ký tự'
			],
			'teacher_id' =>
			[
				'isRequired' => 'Mã giáo viên không được để trống',
				'isNumber' => 'Mã giáo viên phải là kiểu số nguyên'
			],
			'new_teacher_id' =>
			[
				'isRequired' => 'Mã giáo viên không được để trống',
				'isNumber' => 'Mã giáo viên phải là kiểu số nguyên'
			],
			'room_id' =>
			[
				'isRequired' => 'Mã phòng không được để trống',
				'isNumber'=> 'Mã phòng không hợp lệ'
			],
			'new_room_id' =>
			[
				'isRequired' => 'Mã phòng không được để trống',
				'isNumber'=> 'Mã phòng không hợp lệ'
			]
		];
	}
	public function index()
	{
		$classModel = new ClassModel();
		Helper::renderPage('/classes/index.php', [
			'classes' => $classModel -> getAll()
		]);
	}
	public function create()
	{
		echo "CREATE";
	}
	public function store()
	{
		$classModel = new ClassModel();
		$roomClassModel = new RoomClassModel();
		$homeRoomTeacherModel = new HomeRoomTeacherModel();

		//Validation
		$data = [];
		$data['class_id'] = $_POST['class_id'];
		$data['class_name'] = $_POST['class_name'] ?? '';
		$data['academic_year'] = $_POST['academic_year'] ?? '';
		$data['teacher_id'] = $_POST['teacher_id'] ?? '';
		$data['room_id'] = $_POST['room_id'] ?? '';
		$data['semester'] = $_POST['semester'];

		$errors = Validator::validate($data, $this->rules);
		if ($errors) {
            Helper::redirectTo('/classes', [
				'form' => $data,
				'errors' => $errors
			]);
            return;
        }
		

		$classModel->store([
			'class_name' => $data['class_name'],
			'academic_year' => $data['academic_year']
		]);

		$homeRoomTeacherModel->store([
			'teacher_id' => $data['teacher_id'],
			'class_id' => $classModel->getClassID(['class_name' => $data['class_name'],'academic_year'=>$data['academic_year']]),
		]);

		$roomClassModel->store([
            'room_id' => $data['room_id'],
            'class_id' => $classModel->getClassID(['class_name' => $data['class_name'],'academic_year'=>$data['academic_year']]),
			'semester' => $data['semester'],
        ]);

		Helper::redirectTo('/classes', ['success' => 'Thêm mới lớp học thành công']);
	}
	

	public function update()
	{
		$classModel = new ClassModel();
		$roomClassModel = new RoomClassModel();
		$homeRoomTeacherModel = new HomeRoomTeacherModel();

		//Validation
        $data = [];
		$data['class_id'] = $_POST['edited_class_id'];
		$data['class_name'] = $_POST['new_class_name'] ?? '';
		$data['academic_year'] = $_POST['new_academic_year'] ?? '';
		$data['teacher_id'] = $_POST['old_teacher_id'] ?? '';
		$data['new_teacher_id'] = $_POST['new_teacher_id'] ?? '';
		$data['room_id'] = $_POST['old_room_id'] ?? '';
		$data['new_room_id'] = $_POST['new_room_id'] ?? '';
		$data['semester'] = $_POST['old_semester'] ?? '';
		$data['new_semester'] = $_POST['new_semester'] ?? '';


		$errors = Validator::validate($data, $this->rules);
        if ($errors) {
            Helper::redirectTo('classes/create', $errors);
            return;
        }

			$classModel->update([
				'class_id' => $data['class_id'],
				'class_name' => $data['class_name'],
				'academic_year' => $data['academic_year']
			]);
			$homeRoomTeacherModel->update([
				'teacher_id' => $data['teacher_id'],
				'class_id' => $data['edited_class_id'],
				'new_teacher_id' => $data['new_teacher_id'],
			]);
			$roomClassModel->update([
				'class_id' => $data['edited_class_id'],
				'room_id' => $data['old_room_id'],
				'new_room_id' => $data['new_room_id'],
				'semester' => $data['old_semester'],
				'new_semester' => $data['new_semester'],
			]);

			Helper::redirectTo('/classes', ['success' => 'Cập nhật lớp học thành công']);
	}

	public function delete()
	{
		$classModel = new ClassModel();
		$roomClassModel = new RoomClassModel();
		$homeRoomTeacherModel = new HomeRoomTeacherModel();

		//Validation
		$data = [];
        $data['class_id'] = $_POST['class_id'] ?? '';
        $data['teacher_id'] = $_POST['teacher_id'] ?? '';
		$data['room_id'] = $_POST['room_id'] ?? '';
		$data['semester'] = $_POST['semester'] ?? '';

		$errors = Validator::validate($data, $this->rules);
		if ($errors) {
            Helper::redirectTo('classes/create', $errors);
            return;
        }

		$homeRoomTeacherModel->delete([
			'class_id' => $data['class_id'],
			'teacher_id' => $data['teacher_id']
		]);
		$roomClassModel->delete([
			'class_id' => $data['class_id'],
			'room_id' => $data['room_id'],
			'semester' => $data['semester']
		]);
		$classModel->delete($data['class_id']);
		
		Helper::redirectTo('/classes', ['success' => 'Xóa lớp học thành công']);
	}
}
