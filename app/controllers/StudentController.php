<?php

namespace App\controllers;

use PDOException;
use App\models\StudentModel;
use App\utils\{Helper, Validator, Paginator};

class StudentController {
	private array $rules;

	public function __construct() {
		$this->rules = [
			'student_id' => [
				'isRequired' => 'Mã học sinh không được để trống',
				'isNumber' => 'Mã học sinh phải là số'
			],
			'full_name' => [
				'isRequired' => 'Tên học sinh không được để trống',
				'isString' => 'Tên học sinh phải là chuỗi',
				'maxLength:255' => 'Tên học sinh không quá 255 ký tự'
			],
			'date_of_birth' => [
				'isRequired' => 'Ngày sinh không được để trống',
				'isDate' => 'Ngày sinh phải là ngày'
			],
			'address' => [
				'isRequired' => 'Địa chỉ không được để trống',
				'isString' => 'Địa chỉ phải là chuỗi',
				'maxLength:255' => 'Địa chỉ không quá 255 ký tự'
			],
			'parent_phone_number' => [
				'isRequired' => 'Số điện thoại không được để trống',
				'isPhone' => 'Số điện thoại phải là số điện thoại',
				'maxLength:10' => 'Số điện thoại không quá 10 ký tự'
			],
			'class_id' => [
				'isRequired' => 'Mã lớp không được để trống',
				'isNumber' => 'Mã lớp phải là số'
			], 
			'academic_year' => [
				'isRequired' => 'Năm học không được để trống',
				'isString' => 'Năm học phải là chuỗi',
				'maxLength:9' => 'Năm học không quá 9 ký tự'
			]
		];
	}

	public function index() {
		try {
			$studentModel = new StudentModel();
			
			$limit = ($_GET['limit'] && $_GET['limit'] !== 'none') ? (int)$_GET['limit'] : MAX_RECORDS_PER_PAGE;
			$page = $_GET['page'] ? (int)$_GET['page'] : 1;
			$filter = [
				'address' => ($_GET['address'] && $_GET['address'] !== 'none') ? $_GET['address'] : null,
				'class_id' => ($_GET['class_id'] && $_GET['class_id'] !== 'none') ? (int)$_GET['class_id'] : null,
				'academic_year' => ($_GET['academic_year'] && $_GET['academic_year'] !== 'none') ? $_GET['academic_year'] : null
			];

			$totalRecords = $studentModel>getCount($filter);
			
			$paginator = new Paginator($limit, $totalRecords, $page);

			$students = $studentModel->getByFilter($filter, $limit, ($page - 1) * $limit);

			Helper::renderPage('/rooms/index.php', [
				'rooms' => $rooms,
				'pagination' => [
					'prevPage' => $paginator->getPrevPage(),
					'currPage' => $paginator->getCurrPage(),
					'nextPage' => $paginator->getNextPage(),
					'pages' => $paginator->getPages(),
				],
				'filter' => $filter,
				'total' => $totalRecords
			]);
		}
		catch(PDOException $e) {
			Helper::redirectTo('/rooms', [
				'status' => 'danger',
				'message' => 'Lấy dữ liệu phòng học thất bại'
			]);
		}
	}

	public function store() {
		try {
			$roomModel = new RoomModel();

			$data = [];
			$data['room_id'] = $_POST['room_id'] ?? -1;
			$data['room_number'] = $_POST['room_number'] ?? '';
			$data['maximum_capacity'] = $_POST['maximum_capacity'] ?? '';

			$errors = Validator::validate($data, $this->rules);

			if($errors) {
				throw new PDOException('Invalid data');
			}
			$roomModel->store($data);
			Helper::redirectTo('/rooms', [
				'status' => 'success',
				'message' => ((int)$data['room_id'] === -1 ? 'Thêm' : 'Cập nhật') . ' phòng học thành công.'
			]);
		}
		catch(PDOException $e) {
			Helper::redirectTo('/rooms', [
				'form' => $data,
				'errors' => $errors,
				'status' => 'danger',
				'message' => 'Thêm phòng học thất bại'
			]);
		}
	}

	public function delete() {
		try {
			$roomModel = new RoomModel();
			$roomModel->delete((int)$_POST['room_id']);
			Helper::redirectTo('/rooms', [
				'status' => 'success',
				'message' => 'Xóa phòng học thành công'
			]);
		}
		catch(PDOException $e) {
			Helper::redirectTo('/rooms', [
				'status' => 'danger',
				'message' => 'Xóa phòng học thất bại'
			]);
		}
	}
}