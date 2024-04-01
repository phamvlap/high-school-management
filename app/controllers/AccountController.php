<?php

namespace App\controllers;

use PDOException;
use App\models\AccountModel;
use App\utils\{Helper, Validator, Paginator};

class AccountController {
	private $rules;

	public function __construct() {
		$this->rules = [
			'username' => [
				'isRequired' => 'Tên tài khoản không được để trống',
				'isString' => 'Tên tài khoản phải là chuỗi',
				'maxLength:50' => 'Tên tài khoản không được quá 50 ký tự'
			],
			'password' => [
				'isRequired' => 'Mật khẩu không được để trống',
				'isString' => 'Mật khẩu phải là chuỗi',
				'maxLength:255' => 'Mật khẩu không được quá 255 ký tự'
			],
			'type' => [
				'isRequired' => 'Loại tài khoản không được để trống',
				'isString' => 'Loại tài khoản phải là chuỗi',
				'maxLength:50' => 'Loại tài khoản không được quá 50 ký tự'
			]
		];
	}

	public function index() {
		try {
			$accountModel = new AccountModel();
			$limit = 10;
			$page = 1;
			$filter = [
				'username' => isset($_GET['username']) ? $_GET['username'] : null,
				'type' => isset($_GET['type']) ? $_GET['type'] : null
			];
			

			$totalRecords = $accountModel->getCount($filter);
			
			$paginator = new Paginator($limit, $totalRecords, $page);

			$users = $accountModel->getByFilter($filter, $limit, ($page - 1) * $limit);
			
			Helper::renderPage('/users/index.php', [
				'users' => $users,
				'pagination' => [
					'currPage' => $_GET['page'] ?? 1,
					'totalPages' => $paginator->getTotalPages(),
					'prevPage' => $paginator->getPrevPage(),
					'nextPage' => $paginator->getNextPage(),
					'pages' => $paginator->getPages()
				]
			]);
		} catch (PDOException $e) {
			Helper::renderPage('/users/index.php', [
				'status' => 'danger',
				'message' => 'Lấy dữ liệu thất bại'
			]);
		}
	}

	public function store() {
		try {
			$accountModel = new AccountModel();

			// var_dump($_POST);

			$data = [];
			$data['username'] = $_POST['username'] ?? '';
			$data['password'] = $_POST['password'] ?? '';
			$data['type'] = $_POST['type'] ?? '';

			$errors = Validator::validate($data, $this->rules);

			if($errors) {
				throw new PDOException('Thông tin không hợp lệ');
			}

			$accountModel->store([
				'username' => $data['username'],
				'password' => $data['password'],
                'type' => $data['type']
			]);
			Helper::redirectTo('/users', [
				'status' => 'success',
				'message' => 'Thêm tài khoản thành công.'
			]);
		}
		catch(PDOException $e) {
			Helper::redirectTo('/users', [
				'form' => $data,
				'errors' => $errors,
				'status' => 'danger',
				'message' => 'Thêm tài khoản thất bại'
			]);
		}
	}

	public function delete() {
		try {
			$accountModel = new AccountModel();
			$accountModel->delete($_POST['username']);
			Helper::redirectTo('/users', [
				'status' => 'success',
				'message' => 'Xóa tài khoản thành công'
			]);
		}
		catch(PDOException $e) {
			Helper::redirectTo('/users', [
				'status' => 'danger',
				'message' => 'Xóa tài khoản thất bại'
			]);
		}
	}
}