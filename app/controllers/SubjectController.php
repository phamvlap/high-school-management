<?php

namespace App\controllers;

use App\models\SubjectModel;
use App\utils\{Validator, Helper, Paginator};
use PDOException;

class SubjectController
{
    private $rules;
    public function __construct()
    {
        $this->rules = [
            'subject_id' => [
                'isRequired' => 'Mã môn học không được để trống',
                'isNumber' => 'Mã môn học phải là số',
            ],
            'subject_name' =>
            [
                'isRequired' => 'Tên không được để trống',
                'isString' => 'Tên phải là chuỗi',
                'maxLength:255' => 'Tên không được quá 255 ký tự'
            ],
            'grade' =>
            [
                'isRequired' => 'Khối không được để trống',
                'isNumber' => 'Khối phải là số',
            ]
        ];
    }
    public function index() {
		try {
			$subjectModel = new subjectModel();
	
			$limit = (isset($_GET['limit']) && $_GET['limit'] !== 'none') ? (int)$_GET['limit'] : MAX_RECORDS_PER_PAGE;
			$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
			$filter = [
				'subject_name' => (isset($_GET['subject_name']) && $_GET['subject_name'] !== '') ? $_GET['subject_name'] : null,
				'grade' => (!empty($_GET['grade']) && $_GET['grade'] !== '') ? (int)$_GET['grade'] : null,
			];
	
			$totalRecords = $subjectModel->getCount($filter);
	
			$paginator = new Paginator($limit, $totalRecords, $page);
	
			$subjects = $subjectModel->getByFilter($filter, $limit, ($page - 1) * $limit);

            Helper::setIntoSession('download_data', [
				'title' => 'DANH SÁCH MÔN HỌC',
				'header' => ['Mã môn', 'Tên môn', 'Khối'],
				'data' => $subjects
			]);
	
			Helper::renderPage('/subjects/index.php', [
				'subjects' => $subjects,
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
			Helper::renderPage('/subjects/index.php', [
				'status' => 'danger',
				'message' => 'Lấy dữ liệu môn học thất bại'
			]);
		}
	}
    public function create()
    {
        Helper::renderPage('/subjects/create.php');
    }
    public function store()
    {
        try {

            $subjectModel = new SubjectModel();
            // Validation
            $data = [];
            $data['subject_id'] = $_POST['subject_id'] ?? -1;
            $data['subject_name'] = $_POST['subject_name'] ?? '';
            $data['grade'] = $_POST['grade'] ?? '';

            $validator = new Validator();
            $validator->validate($data, $this->rules);
            $errors = Validator::validate($data, $this->rules);
            if ($errors) {

                Helper::redirectTo('/subjects', ['form' => $data, 'errors' => $errors, 'status' => 'danger', 'message' => ' Thêm môn học thất bại']);
                return;
            }
            $subjectModel->store($data);
            Helper::redirectTo('/subjects', [
                'status' => 'success',
                'message' => $data['subject_id'] == '-1' ? 'Thêm môn học thành công' : 'Cập nhật môn học thành công'
            ]);
        } catch (PDOException $e) {
            Helper::redirectTo('/subjects', [
                'form' => $data,
                'errors' => $errors,
                'status' => 'danger',
                'message' => 'Thêm môn học thất bại'
            ]);
        }
    }


    public function delete()
    {
        try {
            $subjectModel = new SubjectModel();
            $subjectModel->delete($_POST['subject_id']);
            Helper::redirectTo('/subjects', ['status' => 'success', 'message' => 'Xóa môn học thành công']);
        } catch (PDOException $e) {
            Helper::redirectTo('/subjects', ['status' => 'danger', 'message' => 'Xóa môn học thất bại']);
        }
    }
}
