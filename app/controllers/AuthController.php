<?php

namespace App\controllers;

use App\utils\{Helper, Paginator, Validator};
use Gregwar\Captcha\CaptchaBuilder;
use App\models\AccountModel;
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
		echo 'auth create register';
	}
	public function register()
	{
		echo 'auth register';
	}
	public function createLogin()
	{
		try {
			// Generate captcha
			$builder = new CaptchaBuilder;
			$builder->build();
			Helper::setIntoSession('captcha', $builder->getPhrase());

			Helper::renderPage('/auth/login.php', [
				'captcha' => $builder->inline(),
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
	public function login()
	{
		try {
			// Validate captcha
			$captcha = $_POST['captcha'];
			$phrase = Helper::getFromSession('captcha');
			/*if ($captcha !== $phrase) {
				Helper::redirectTo(
					'/login',
					[
						'status' => 'danger',
						'message' => 'Mã xác nhận không đúng',
						'form' => ['username' => $_POST['username']],
						'errors' => ['captcha' => 'Mã xác nhận không đúng'],
						'captcha' => $captcha,
						'phrase' => $phrase
					]
				);
				return;
			}*/

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

			if (!$account || !($password == $account['password'])) {
				// if ($account && password_verify($password, $account['password'])) {
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
			Helper::redirectTo('/', [
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
