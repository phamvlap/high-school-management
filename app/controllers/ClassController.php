<?php

namespace App\controllers;

use App\models\{ClassModel,  HomeRoomTeacherModel};
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
				'isRequired' => 'Mã lớp không được để trống',
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
			]
		];
	}
	public function index()
	{
		try {
			$classModel = new ClassModel();

			$limit = (isset($_GET['limit']) && $_GET['limit'] !== 'none') ? (int)$_GET['limit'] : MAX_RECORDS_PER_PAGE;
			$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
			$filter = [
				'class_name' => (isset($_GET['class_name']) && $_GET['class_name'] !== '') ? $_GET['class_name'] : null,
				'grade' => (isset($_GET['grade']) && $_GET['grade'] !== '') ? $_GET['grade'] : null,
				'academic_year' => (isset($_GET['academic_year']) && $_GET['academic_year'] !== '') ? $_GET['academic_year'] : null,
			];


			$totalRecords = $classModel->getCount($filter);
			$paginator = new Paginator($limit, $totalRecords, $page);
			$classes = $classModel->getByFilter($filter, $limit, ($page - 1) * $limit);

			Helper::setIntoSession('download_data', [
				'title' => 'DANH SÁCH LỚP',
				'header' => ['Mã lớp', 'Lớp', 'Năm học', 'Mã giáo viên', 'Tên giáo viên'],
				'data' => $classes
			]);

			Helper::renderPage('/classes/index.php', [
				'classes' => $classes,
				'pagination' => [
					'prevPage' => $paginator->getPrevPage(),
					'currPage' => $paginator->getCurrPage(),
					'nextPage' => $paginator->getNextPage(),
					'pages' => $paginator->getPages(),
				],
				'filter' => $filter,
				'total' => $totalRecords,
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
			$homeRoomTeacherModel = new HomeRoomTeacherModel();

			//Validation
			$data = [];
			$data['class_id'] = $_POST['class_id'] ?? -1;
			$data['class_name'] = $_POST['class_name'] ?? '';
			$data['academic_year'] = $_POST['academic_year'] ?? '';
			$data['teacher_id'] = $_POST['teacher_id'] ?? '';

			$errors = Validator::validate($data, $this->rules);
			if ($errors) {
				throw new PDOException('Thông tin không hợp lệ');
			}

			$classModel->store($data);
			
			$isUpdate = true;

			if ($data['class_id'] == -1) {
				$data['class_id'] = $classModel->getClassId();
				$isUpdate = false;
			}

			$homeRoomTeacherModel->store($data);

			Helper::redirectTo('/classes', [
				'status' => 'success',
				'message' => (!$isUpdate ? 'Thêm' : 'Cập nhật') . ' lớp học thành công'
			]);
		} catch (PDOException $e) {
			Helper::redirectTo('/classes', [
				'form' => $data,
				'errors' => $errors,
				'status' => 'danger',
				'message' => (!$isUpdate ? 'Thêm' : 'Cập nhật') . ' lớp học thất bại'
			]);
		}
	}

	public function delete()
	{
		try {
			$classModel = new ClassModel();
			if (is_numeric((int)($_POST['class_id'] ?? -1)) === false) {
				throw new PDOException('Thông tin không hợp lệ');
			}
			$classModel->delete((int)$_POST['class_id']);
			Helper::redirectTo('/classes', [
				'status' => 'success',
				'message' => 'Xóa lớp học thành công'
			]);
		} catch (PDOException $e) {
			Helper::redirectTo('/classes', [
				'status' => 'danger',
				'message' => 'Xóa lớp học thất bại ' . $e->getMessage()
			]);
		}
	}
}
