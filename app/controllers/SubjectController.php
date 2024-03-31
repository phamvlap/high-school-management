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
    public function index()
    {
        $subjectModel = new SubjectModel();
        $paginator = new Paginator(
            $_GET['limit'] ?? 10,
            $subjectModel->count(),
            $_GET['page'] ?? 1
        );
        Helper::renderPage('/subjects/index.php', [
            'subjects' => $subjectModel->getAll(),
            'pagination' => [
                'currPage' => $_GET['page'] ?? 1,
                'totalPages' => $paginator->getTotalPages(),
                'prevPage' => $paginator->getPrevPage(),
                'nextPage' => $paginator->getNextPage(),
                'pages' => $paginator->getPages()
            ]
        ]);
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
            $data['subject_id'] = $_POST['subject_id'] ?? '-1';
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
