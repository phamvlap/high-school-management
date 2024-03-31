<?php

namespace App\controllers;

use App\models\TeacherModel;
use App\utils\{Validator, Helper};
use PDOException;

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
				'maxLength:255' => 'Tên không được quá 255 ký tự'
			],
			'date_of_birth' =>
			[
				'isRequired' => 'Ngày sinh không được để trống',
				// 'isDate' => 'Ngày sinh không hợp lệ'
			],
			'address' =>
			[
				'isRequired' => 'Địa chỉ không được để trống',
				'isString' => 'Địa chỉ phải là chuỗi',
				'maxLength:255' => 'Địa chỉ không được quá 255 ký tự'
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
		try {
			$teacherModel = new TeacherModel();
			Helper::renderPage('/teachers/index.php', [
				'teachers' => $teacherModel->getAll()
			]);
		} catch (PDOException $e) {
			Helper::renderPage('/teachers/index.php', [
				'status' => 'danger',
				'message' => 'Lấy dữ liệu thất bại'
			]);
		}
	}

	public function store()
	{
		try {
			$teacherModel = new TeacherModel();

			// Validation
			$data = [];
			$data['teacher_id'] = $_POST['teacher_id'] ?? '-1';
			$data['full_name'] = $_POST['full_name'] ?? '';
			$data['date_of_birth'] = $_POST['date_of_birth'] ?? '';
			$data['address'] = $_POST['address'] ?? '';
			$data['phone_number'] = $_POST['phone_number'] ?? '';

			$errors = Validator::validate($data, $this->rules);

			if ($errors) {
				throw new PDOException('Thông tin không hợp lệ');
			}


			$teacherModel->store($data);
			Helper::redirectTo('/teachers', [
				'status' => 'success',
				'message' => $data['teacher_id'] == '-1' ? 'Thêm giáo viên thành công' : 'Cập nhật giáo viên thành công'
			]);
		} catch (PDOException $e) {
			Helper::redirectTo('/teachers', [
				'form' => $data,
				'errors' => $errors,
				'status' => 'danger',
				'message' => 'Thêm giáo viên thất bại'
			]);
		}
	}

	public function delete()
	{
		try {
			$teacherModel = new TeacherModel();
			$teacherModel->delete($_POST['teacher_id']);
			Helper::redirectTo('/teachers', [
				'status' => 'success',
				'message' => 'Xóa giáo viên thành công'
			]);
		} catch (PDOException $e) {
			Helper::redirectTo('/teachers', [
				'status' => 'danger',
				'message' => 'Xóa giáo viên thất bại'
			]);
		}
	}
}
