<?php

namespace App\controllers;

use App\utils\{Helper, Paginator, Validator};
use App\models\{AccountModel, StudentModel};
use PDOException;

class AuthController
{
	private $rules;

	public function __construct()
	{
		$this->rules = [
			'username' => ['required' => 'Vui lòng nhập tên đăng nhập'],
			'password' => ['required' => 'Vui lòng nhập mật khẩu']
		];
	}

	public function createRegister()
	{
		Helper::renderPage('/auth/register.php');
	}

	public function register()
	{
		try {
			$phoneNumber = $_POST['phone_number'];
			$password = $_POST['password'];
			$confirmPassword = $_POST['confirm_password'];

			$errors = Validator::validate([
				'phone_number' => $phoneNumber,
				'password' => $password,
				'confirm_password' => $confirmPassword
			], [
				'phone_number' => [
					'isRequired' => 'Vui lòng nhập số điện thoại',
					'isPhoneNumber' => 'Số điện thoại không hợp lệ'
				],
				'password' => [
					'isRequired' => 'Vui lòng nhập mật khẩu',
					'isPassword' => 'Mật khẩu phải chứa ít nhất 8 ký tự, bao gồm chữ cái và số'
				],
				'confirm_password' => [
					'isRequired' => 'Vui lòng nhập lại mật khẩu'
				],
			]);

			if ($password !== $confirmPassword) {
				$errors['confirm_password'] = 'Mật khẩu không khớp';
			}

			if ($errors) {
				Helper::redirectTo(
					'/register',
					[
						'form' => ['phone_number' => $phoneNumber],
						'errors' => $errors
					]
				);
			}

			$accountModel = new AccountModel();
			$oldAccount = $accountModel->getByUsername($phoneNumber);

			if ($oldAccount) {
				Helper::redirectTo(
					'/register',
					[
						'status' => 'danger',
						'message' => 'Tài khoản đã tồn tại',
						'form' => ['phone_number' => $phoneNumber]
					]
				);
			}

			$studentModel = new StudentModel();
			$student = $studentModel->getByPhoneNumber($phoneNumber);

			if ($student) {
				$accountModel->store([
					'username' => $student['parent_phone_number'],
					'password' => $password,
					'type' => 'parent',
				]);

				Helper::redirectTo(
					'/login',
					[
						'status' => 'success',
						'message' => 'Tạo tài khoản thành công',
						'form' => ['username' => $phoneNumber]
					]
				);
			}
			Helper::redirectTo(
				'/register',
				[
					'status' => 'danger',
					'message' => 'Số điện thoại không tồn tại'
				]
			);
		} catch (PDOException $e) {
			Helper::redirectTo(
				'/register',
				[
					'status' => 'danger',
					'message' => 'Không thể tạo tài khoản.'
				]
			);
		}
	}
	public function createLogin()
	{
		try {

			Helper::renderPage('/auth/login.php', []);
		} catch (PDOException $e) {
			Helper::redirectTo(
				'/login',
				[
					'status' => 'danger',
					'message' => 'Đã có lỗi xảy ra'
				]
			);
		}
	}
	public function login()
	{
		try {

			$username = $_POST['username'];
			$password = $_POST['password'];

			if (Validator::validate(['username' => $username, 'password' => $password], $this->rules)) {
				Helper::redirectTo(
					'/login',
					[
						'form' => ['username' => $username],
						'errors' => ['password' => 'Vui lòng nhập đầy đủ thông tin']
					]
				);
				return;
			}

			$accountModel = new AccountModel();
			$account = $accountModel->getByUsername($username);

			echo $account;

			if (!$account || !($password == $account['password'])) {
				// if ($account && .password_verify($password, $account['password'])) {
				Helper::redirectTo(
					'/login',
					[
						'status' => 'danger',
						'message' => 'Tài khoản hoặc mật khẩu không đúng',
						'form' => ['username' => $username],
						'errors' => ['password' => 'Thông tin đăng nhập không hợp lệ']
					]
				);
			}

			Helper::setIntoSession('auth', [
				'username' => $account['username'],
				'type' => $account['type']
			]);

			$url = '/';
			if ($account['type'] === 'parent') {
				$url = '/parents';
			}
			Helper::redirectTo($url, [
				'status' => 'success',
				'message' => 'Đăng nhập thành công',
			]);
		} catch (PDOException $e) {
			Helper::redirectTo(
				'/login',
				[
					'status' => 'danger',
					'message' => 'Đã có lỗi xảy ra'
				]
			);
		}
	}
	public function logout()
	{
		Helper::removeFromSession('auth');
		Helper::redirectTo('/login', [
			'status' => 'success',
			'message' => 'Đăng xuất thành công'
		]);
	}
}
