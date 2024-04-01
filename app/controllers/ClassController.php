<?php

namespace App\controllers;

use App\models\{ClassModel, RoomClassModel, HomeRoomTeacherModel, StudentModel};
use App\utils\{Validator, Helper, Paginator};
use PDOException;

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
				'isString' => 'Tên phải là chuỗi'
			],
			'academic_year' =>
			[
				'isRequired' => 'Năm học không được để trống',
				'isString' => 'Năm học không hợp lệ, định dạng: XXXX-YYYY',
			],
			'teacher_id' =>
			[
				'isRequired' => 'Mã giáo viên không được để trống'
			],
			'room_id' =>
			[
				'isRequired' => 'Mã phòng không được để trống'
			]
		];
	}
	public function index()
	{
		try {
			$classModel = new ClassModel();
			$paginator = new Paginator(
				$_GET['limit'] ?? 10,
				$classModel->count(),
				$_GET['page'] ?? 1
			);
			Helper::renderPage('/classes/index.php', [
				'classes' => $classModel->getAll(),
				'pagination' => [
					'currPage' => $_GET['page'] ?? 1,
					'totalPages' => $paginator->getTotalPages(),
					'prevPage' => $paginator->getPrevPage(),
					'nextPage' => $paginator->getNextPage(),
					'pages' => $paginator->getPages()
				]
			]);
		} catch (PDOException $e) {
			Helper::renderPage('/classes/index.php', [
				'status' => 'danger',
				'message' => 'Lấy dữ liệu thất bại'
			]);
		}
	}
	public function store()
	{
		try {
			$classModel = new ClassModel();
			$roomClassModel = new RoomClassModel();
			$homeRoomTeacherModel = new HomeRoomTeacherModel();

			//Validation
			$data = [];
			$data['class_id'] = $_POST['class_id'] ?? '-1';
			$data['class_name'] = $_POST['class_name'] ?? '';
			$data['academic_year'] = $_POST['academic_year'] ?? '';
			$data['teacher_id'] = $_POST['teacher_id'] ?? '';
			$data['room_id'] = $_POST['room_id'] ?? '';
			$data['semester'] = $_POST['semester'];

			$errors = Validator::validate($data, $this->rules);
			if ($errors) {
				throw new PDOException('Thông tin không hợp lệ');
			}
			
			$classModel->store([
				'class_name' => $data['class_name'],
				'academic_year' => $data['academic_year']
			]);

			$classID = $classModel->getClassID(['class_name' => $data['class_name'],'academic_year'=>$data['academic_year']]);
			var_dump($classID);
		
			$homeRoomTeacherModel->store([
				'teacher_id' => $data['teacher_id'],
				'class_id' => $classID,
			]);

			$roomClassModel->store([
				'room_id' => $data['room_id'],
				'class_id' => $classID,
				'semester' => $data['semester'],
			]);

			Helper::redirectTo('/classes', [
				'status' => 'success',
				'message' => 'Thêm mới lớp học thành công'
			]);
		} catch (PDOException $e) {
			Helper::redirectTo('/classes', [
				'form' => $data,
				'errors' => $errors,
				'status' => 'danger',
				'message' => 'Thêm mới lớp học thất bại'
			]);
		}
	}
	

	// public function update()
	// {
	// 	$classModel = new ClassModel();
	// 	$roomClassModel = new RoomClassModel();
	// 	$homeRoomTeacherModel = new HomeRoomTeacherModel();

	// 	//Validation
    //     $data = [];
	// 	$data['class_id'] = $_POST['class_id'];
	// 	$data['class_name'] = $_POST['new_class_name'] ?? '';
	// 	$data['academic_year'] = $_POST['new_academic_year'] ?? '';
	// 	$data['teacher_id'] = $_POST['old_teacher_id'] ?? '';
	// 	$data['new_teacher_id'] = $_POST['new_teacher_id'] ?? '';
	// 	$data['room_id'] = $_POST['old_room_id'] ?? '';
	// 	$data['new_room_id'] = $_POST['new_room_id'] ?? '';
	// 	$data['semester'] = $_POST['old_semester'] ?? '';
	// 	$data['new_semester'] = $_POST['new_semester'] ?? '';


	// 	$errors = Validator::validate($data, $this->rules);
    //     if ($errors) {
    //         Helper::redirectTo('classes/create', $errors);
    //         return;
    //     }

	// 		$classModel->update([
	// 			'class_id' => $data['class_id'],
	// 			'class_name' => $data['class_name'],
	// 			'academic_year' => $data['academic_year']
	// 		]);
	// 		$homeRoomTeacherModel->update([
	// 			'teacher_id' => $data['teacher_id'],
	// 			'class_id' => $data['edited_class_id'],
	// 			'new_teacher_id' => $data['new_teacher_id'],
	// 		]);
	// 		$roomClassModel->update([
	// 			'class_id' => $data['edited_class_id'],
	// 			'room_id' => $data['old_room_id'],
	// 			'new_room_id' => $data['new_room_id'],
	// 			'semester' => $data['old_semester'],
	// 			'new_semester' => $data['new_semester'],
	// 		]);

	// 		Helper::redirectTo('/classes', ['success' => 'Cập nhật lớp học thành công']);
	// }

	public function delete()
	{
		try {
			$classModel = new ClassModel();
			$roomClassModel = new RoomClassModel();
			$homeRoomTeacherModel = new HomeRoomTeacherModel();
			$studentModel = new StudentModel();

			//Validation
			$data = [];
			$data['class_id'] = $_POST['class_id'] ?? '';
			$data['teacher_id'] = $_POST['teacher_id'] ?? '';
			$data['room_id'] = $_POST['room_id'] ?? '';
			$data['semester'] = $_POST['semester'] ?? '';
			$data['new_class_id'] = $_POST['new_class_id'] ?? '';

			// $errors = Validator::validate($data, $this->rules);
			// if ($errors) {
			// 	throw new PDOException('Thông tin không hợp lệ');
			// }

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
			
			Helper::redirectTo('/classes', [
				'status' => 'success',
				'success' => 'Xóa lớp học thành công'
			]);
		} catch (PDOException $e) {
			Helper::redirectTo('/classes', [
				'status' => 'danger',
				'message' => 'Xóa lớp học thất bại'
			]);
		}
	}
}
