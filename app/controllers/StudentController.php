<?php

namespace App\controllers;

use PDOException;
use App\models\StudentModel;
use App\utils\{Helper, Validator, Paginator};

class StudentController
{
	private $rules;

	public function __construct()
	{
		$this->rules = [
			'full_name' => [
				'isRequired' => 'Họ và tên không được để trống',
				'isString' => 'Họ và tên phải là chuỗi',
				'maxLength:255' => 'Họ và tên không được quá 255 ký tự'
			],
			'date_of_birth' => [
				'isRequired' => 'Ngày sinh không được để trống'
			],
			'address' => [
				'isRequired' => 'Địa chỉ không được để trống',
				'isString' => 'Địa chỉ phải là chuỗi'
			],
			'parent_phone_number' => [
				'isRequired' => 'Số điện thoại không được để trống',
			],
			'class_id' => [
				'isRequired' => 'Mã lớp không được để trống',
				'isNumber' => 'Mã lớp phải là số'
			]
		];
	}

	public function index()
	{
		try {
			$studentModel = new StudentModel();

			$limit = (isset($_GET['limit']) && $_GET['limit'] !== 'none') ? (int)$_GET['limit'] : MAX_RECORDS_PER_PAGE;
			$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
			$filter = [
				'full_name' => (isset($_GET['full_name']) && $_GET['full_name'] !== '') ? $_GET['full_name'] : null,
				'address' => (isset($_GET['address']) && $_GET['address'] !== '') ? $_GET['address'] : null,
				'class_id' => (isset($_GET['class_id']) && $_GET['class_id'] !== '') ? $_GET['class_id'] : null,
				'academic_year' => (isset($_GET['academic_year']) && $_GET['academic_year'] !== '') ? $_GET['academic_year'] : null,
				'is_order_by_name' => (isset($_GET['is_order_by_name']) && $_GET['is_order_by_name'] !== 'none') ? (int)$_GET['is_order_by_name'] : null
			];

			$totalRecords = $studentModel->getCount($filter);

			$paginator = new Paginator($limit, $totalRecords, $page);

			$students = $studentModel->getByFilter($filter, $limit, ($page - 1) * $limit);

			// download data
			$downloadData = [];
			foreach ($students as $student) {
				$downloadData[] = [
					$student['student_id'],
					$student['full_name'],
					$student['date_of_birth'],
					$student['address'],
					$student['parent_phone_number'],
					$student['class_name'],
					$student['academic_year']
				];
			}

			Helper::setIntoSession('download_data', [
				'title' => 'DANH SÁCH HỌC SINH',
				'header' => ['Mã số', 'Họ và tên', 'Ngày sinh', 'Địa chỉ', 'SĐT cha mẹ', 'Lớp', 'Năm học'],
				'data' => $downloadData,
			]);

			Helper::renderPage('/students/index.php', [
				'students' => $students,
				'pagination' => [
					'prevPage' => $paginator->getPrevPage(),
					'currPage' => $paginator->getCurrPage(),
					'nextPage' => $paginator->getNextPage(),
					'pages' => $paginator->getPages(),
				],
				'filter' => $filter,
				'total' => $totalRecords
			]);
		} catch (PDOException $e) {
			Helper::renderPage('/students/index.php', [
				'status' => 'danger',
				'message' => 'Lấy dữ liệu học sinh thất bại'
			]);
		}
	}

	public function store()
	{
		try {
			$studentModel = new StudentModel();


			$data = [];
			$data['student_id'] = $_POST['student_id'] ?? -1;
			$data['full_name'] = $_POST['full_name'] ?? '';
			$data['date_of_birth'] = $_POST['date_of_birth'] ?? '';
			$data['address'] = $_POST['address'] ?? '';
			$data['parent_phone_number'] = $_POST['parent_phone_number'] ?? '';
			$data['class_id'] = $_POST['class_id'] ?? '';

			$errors = Validator::validate($data, $this->rules);
			if ($errors) {
				throw new PDOException('Thông tin không hợp lệ');
			}


			$studentModel->store([
				'student_id' => $data['student_id'],
				'full_name' => $data['full_name'],
				'date_of_birth' => $data['date_of_birth'],
				'address' => $data['address'],
				'parent_phone_number' => $data['parent_phone_number'],
				'class_id' => $data['class_id']
			]);

			Helper::redirectTo('/students', [
				'status' => 'success',
				'message' => ((int)$data['student_id'] === -1 ? 'Thêm' : 'Cập nhật') . ' học sinh thành công.'
			]);
		} catch (PDOException $e) {
			Helper::redirectTo('/students', [
				'form' => $data,
				'errors' => $errors,
				'status' => 'danger',
				'message' => 'Thêm học sinh thất bại',
				'sql_error' => $e->getMessage()
			]);
		}
	}

	public function delete()
	{
		try {
			$studentModel = new StudentModel();
			var_dump($_POST);
			$studentModel->delete((int)$_POST['student_id']);
			Helper::redirectTo('/students', [
				'status' => 'success',
				'message' => 'Xóa học sinh thành công'
			]);
		} catch (PDOException $e) {
			Helper::redirectTo('/students', [
				'status' => 'danger',
				'message' => 'Xóa học sinh thất bại'
			]);
		}
	}
}
