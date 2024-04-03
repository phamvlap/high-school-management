<?php

namespace App\controllers;

use App\models\MarkModel;
use App\utils\{Validator, Helper, Paginator};
use PDOException;

class MarkController
{
	private $rules;
	public function __construct()
	{
		$this->rules = [
			'student_id' => [
				'isRequired' => 'Mã học sinh không được để trống',
				'isNumber' => 'Mã học sinh phải là số'
			],
			'subject_id' => [
				'isRequired' => 'Mã môn học không được để trống',
				'isNumber' => 'Mã môn học phải là số'
			],
			'semester' => [
				'isRequired' => 'Học kỳ không được để trống',
				'isNumber' => 'Học kỳ phải là số'
			],
			'oral_score' => [
				// 'isRequired' => 'Điểm miệng không được để trống',
				'isNumber' => 'Điểm miệng phải là số',
				'isScore' => 'Điểm miệng phải từ 0 đến 10'
			],
			'_15_minutes_score' => [
				// 'isRequired' => 'Điểm 15 phút không được để trống',
				'isNumber' => 'Điểm 15 phút phải là số',
				'isScore' => 'Điểm miệng phải từ 0 đến 10'
			],
			'_1_period_score' => [
				// 'isRequired' => 'Điểm 1 tiết không được để trống',
				'isNumber' => 'Điểm 1 tiết phải là số',
				'isScore' => 'Điểm miệng phải từ 0 đến 10'
			],
			'semester_score' => [
				// 'isRequired' => 'Điểm học kỳ không được để trống',
				'isNumber' => 'Điểm học kỳ phải là số',
				'isScore' => 'Điểm miệng phải từ 0 đến 10'
			]
		];
	}

	public function index()
	{
		try {
			$markModel = new MarkModel();

			$limit = (isset($_GET['limit']) && $_GET['limit'] !== 'none') ? (int)$_GET['limit'] : MAX_RECORDS_PER_PAGE;
			$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
			$filter = [
				'student_id' => (!empty($_GET['student_id']) && $_GET['student_id'] !== 'none') ? (int)$_GET['student_id'] : null,
				'subject_id' => (!empty($_GET['subject_id']) && $_GET['subject_id'] !== '') ? (int)$_GET['subject_id'] : null,
				'semester' => (!empty($_GET['semester']) && $_GET['semester'] !== 'none') ? (int)$_GET['semester'] : null,
			];

			$totalRecords = $markModel->getCount($filter);

			$paginator = new Paginator($limit, $totalRecords, $page);

			$marks = $markModel->getByFilter($filter, $limit, ($page - 1) * $limit);

			$downloadData = [];
			foreach ($marks as $mark) {
				$downloadData[] = [
					'student_id' => $mark['student_id'],
					'full_name' => $mark['full_name'],
					'subject_name' => $mark['subject_name'],
					'grade' => $mark['grade'],
					'subject_id' => $mark['subject_id'],
					'oral_score' => $mark['oral_score'],
					'_15_minutes_score' => $mark['_15_minutes_score'],
					'_1_period_score' => $mark['_1_period_score'],
					'semester_score' => $mark['semester_score'],
					'avr_score' => $mark['avr_score'] ?? '--',
					'semester' => $mark['semester'],
					'academic_year' => $mark['academic_year'],
				];
			}


			Helper::setIntoSession('download_data', [
				'title' => 'BẢNG ĐIỂM',
				'header' => ['Mã số', 'Họ tên', 'Môn học',  'Khối', 'Mã môn', 'Miệng', '15 phút', 'Một tiết', 'Cuối kì', 'Điểm trung bình', 'Học kì', 'Năm học'],
				'data' => $downloadData
			]);

			Helper::renderPage('/marks/index.php', [
				'marks' => $marks,
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
			Helper::renderPage('/marks/index.php', [
				'status' => 'danger',
				'message' => 'Lấy dữ liệu môn học thất bại'
			]);
		}
	}

	public function store()
	{
		try {
			$markModel = new MarkModel();
			$data = [];
			$data['student_id'] = (int)$_POST['student_id'] ?? '';
			$data['subject_id'] = (int)$_POST['subject_id'] ?? '';
			$data['semester'] = (int)$_POST['semester'] ?? '';
			$data['oral_score'] = (float)$_POST['oral_score'] ?? null;
			$data['_15_minutes_score'] = (float)$_POST['_15_minutes_score'] ?? null;
			$data['_1_period_score'] = (float)$_POST['_1_period_score'] ?? null;
			$data['semester_score'] = (float)$_POST['semester_score'] ?? null;
			$validator = new Validator();
			$errors = $validator->validate($data, $this->rules);
			if ($errors) {
				Helper::redirectTo('/marks', ['form' => $data, 'errors' => $errors, 'status' => 'danger', 'message' => ' Cập nhật điểm thất bại']);
				return;
			}
			$markModel->store($data);
			Helper::redirectTo('/marks', ['status' => 'success', 'message' => 'Cập nhật điểm thành công']);
		} catch (PDOException $e) {

			Helper::redirectTo('/marks', ['form' => $data, 'errors' => $errors, 'status' => 'danger', 'message' => 'Cập nhật điểm thất bại']);
		}
	}

	public function delete()
	{
		try {
			$markModel = new MarkModel();
			$data = [];
			$data['student_id'] = (int)$_POST['student_id'] ?? '';
			$data['subject_id'] = (int)$_POST['subject_id'] ?? '';
			$data['semester'] = (int)$_POST['semester'] ?? '';
			$markModel->delete($data['student_id'], $data['subject_id'], $data['semester']);
			Helper::redirectTo('/marks', ['status' => 'success', 'message' => 'Xóa điểm thành công']);
		} catch (PDOException $e) {
			Helper::redirectTo('/marks', ['status' => 'danger', 'message' => 'Xóa điểm thất bại']);
		}
	}
}
