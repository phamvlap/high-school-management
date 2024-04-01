<?php

namespace App\controllers;

use PDOException;
use App\models\RoomModel;
use App\utils\{Helper, Validator, Paginator};

class RoomController
{
	private array $rules;

	public function __construct()
	{
		$this->rules = [
			'room_number' => [
				'isRequired' => 'Số phòng không được để trống',
				'isString' => 'Số phòng phải là chuỗi',
				'maxLength:50', 'message' => 'Số phòng không được quá 50 ký tự'
			],
			'maximum_capacity' => [
				'isRequired' => 'Sức chứa không được để trống',
				'isNumber' => 'Sức chứa phải là số',
				'minLength:1', 'message' => 'Sức chứa phải lớn hơn 0'
			]
		];
	}

	public function index()
	{
		try {
			$roomModel = new RoomModel();

			$limit = (isset($_GET['limit']) && $_GET['limit'] !== 'none') ? (int)$_GET['limit'] : MAX_RECORDS_PER_PAGE;
			$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
			$filter = [
				'room_number' => (isset($_GET['room_number']) && $_GET['room_number'] !== 'none') ? (int)$_GET['room_number'] : null,
				'min_capacity' => (isset($_GET['min_capacity']) && $_GET['min_capacity'] !== 'none') ? (int)$_GET['min_capacity'] : null,
				'max_capacity' => (isset($_GET['max_capacity']) && $_GET['max_capacity'] !== 'none') ? (int)$_GET['max_capacity'] : null,
				'is_sort_by_capacity' => (isset($_GET['sort']) && $_GET['sort'] !== 'none') ? (int)$_GET['sort'] : null
			];

			$totalRecords = $roomModel->getCount($filter);

			$paginator = new Paginator($limit, $totalRecords, $page);

			$rooms = $roomModel->getByFilter($filter, $limit, ($page - 1) * $limit);

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
		} catch (PDOException $e) {
			Helper::redirectTo('/rooms', [
				'status' => 'danger',
				'message' => 'Lấy dữ liệu phòng học thất bại'
			]);
		}
	}

	public function store()
	{
		try {
			$roomModel = new RoomModel();

			$data = [];
			$data['room_id'] = $_POST['room_id'] ?? -1;
			$data['room_number'] = $_POST['room_number'] ?? '';
			$data['maximum_capacity'] = $_POST['maximum_capacity'] ?? '';

			$errors = Validator::validate($data, $this->rules);

			if ($errors) {
				throw new PDOException('Invalid data');
			}
			$roomModel->store($data);
			Helper::redirectTo('/rooms', [
				'status' => 'success',
				'message' => ((int)$data['room_id'] === -1 ? 'Thêm' : 'Cập nhật') . ' phòng học thành công.'
			]);
		} catch (PDOException $e) {
			Helper::redirectTo('/rooms', [
				'form' => $data,
				'errors' => $errors,
				'status' => 'danger',
				'message' => 'Thêm phòng học thất bại'
			]);
		}
	}

	public function delete()
	{
		try {
			$roomModel = new RoomModel();
			$roomModel->delete((int)$_POST['room_id']);
			Helper::redirectTo('/rooms', [
				'status' => 'success',
				'message' => 'Xóa phòng học thành công'
			]);
		} catch (PDOException $e) {
			Helper::redirectTo('/rooms', [
				'status' => 'danger',
				'message' => 'Xóa phòng học thất bại'
			]);
		}
	}
}
