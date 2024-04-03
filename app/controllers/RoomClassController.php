<?php

namespace App\controllers;

use App\models\RoomClassModel;
use App\utils\{Validator, Helper, Paginator};
use PDOException;

class RoomClassController
{
    private $rules;

    public function __construct()
    {
        $this->rules = [
            'room_id' => [
                'required' => 'Mã phòng không được để trống',
                'isNumber' => 'Mã phòng phải là số',
            ],
            'class_id' => [
                'required' => 'Mã lớp không được để trống',
                'isNumber' => 'Mã lớp phải là số',
            ],
            'semester' => [
                'required' => 'Học kì không được để trống',
            ],
        ];
    }

    public function index()
    {
        try {
            $roomClassModel = new RoomClassModel();

            $limit = (isset($_GET['limit']) && $_GET['limit'] !== 'none') ? (int)$_GET['limit'] : MAX_RECORDS_PER_PAGE;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $filter = [
                'room_id' => !empty($_GET['room_id']) ? (int)$_GET['room_id'] : null,
                'class_id' => !empty($_GET['class_id']) ? (int)$_GET['class_id'] : null,
                'grade' => !empty($_GET['grade']) ? (int)$_GET['grade'] : null,
                'semester' => !empty($_GET['semester']) ? (int)$_GET['semester'] : null,
                'academic_year' => !empty($_GET['academic_year']) ? $_GET['academic_year'] : null,
                'is_sort_by_classname' => (isset($_GET['sort']) && $_GET['sort'] !== 'none') ? (int)$_GET['sort'] : -1
            ];


            $totalRecords = $roomClassModel->getCount($filter);
            $paginator = new Paginator($limit, $totalRecords, $page);
            $roomClass = $roomClassModel->getByFilter($filter, $limit, ($page - 1) * $limit);

            // Download data
            $downloadData = [];
            foreach ($roomClass as $rc) {
                $downloadData[] = [
                    'room_id' => $rc['room_id'],
                    'room_number' => $rc['room_number'],
                    'class_id' => $rc['class_id'],
                    'class_name' => $rc['class_name'],
                    'semester' => $rc['semester'],
                    'academic_year' => $rc['academic_year'],
                ];
            }

            Helper::setIntoSession('download_data', [
                'title' => 'DANH SÁCH PHÒNG LỚP',
                'header' => ['Mã phòng', 'Số phòng', 'Mã lớp', 'Tên lớp', 'Học kì', 'Năm học'],
                'data' => $downloadData,
            ]);


            Helper::renderPage('/roomclass/index.php', [
                'roomClass' => $roomClass,
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
            Helper::renderPage('/roomclass/index.php', [
                'status' => 'danger',
                'message' => 'Lấy dữ liệu thất bại'
            ]);
        }
    }

    public function store()
    {
        try {
            $roomClassModel = new RoomClassModel();

            // Validation
            $data = [];
            $data = [
                'room_id' => $_POST['room_id'],
                'class_id' => $_POST['class_id'],
                'semester' => $_POST['semester'],
            ];

            $errors = Validator::validate($data, $this->rules);

            if ($errors) {
                throw new PDOException('Thông tin không hợp lệ');
            }


            $roomClassModel->store($data);
            Helper::redirectTo('/roomclass', [
                'status' => 'success',
                'message' => 'Cập nhật phòng - lớp thành công'
            ]);
        } catch (PDOException $e) {
            Helper::redirectTo('/roomclass', [
                'form' => $data,
                'errors' => $errors,
                'status' => 'danger',
                'message' => 'Cập nhật phòng - lớp thất bại',
            ]);
        }
    }

    public function delete()
    {
        try {
            $roomClassModel = new RoomClassModel();
            $roomClassModel->delete([
                'class_id' => $_POST['class_id'],
                'semester' => $_POST['semester']
            ]);
            Helper::redirectTo('/roomclass', [
                'status' => 'success',
                'message' => 'Xóa phòng - lớp thành công'
            ]);
        } catch (PDOException $e) {
            Helper::redirectTo('/roomclass', [
                'status' => 'danger',
                'message' => 'Xóa phòng - lớp thất bại'
            ]);
        }
    }
}
