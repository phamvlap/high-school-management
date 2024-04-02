<?php

namespace App\controllers;

use App\utils\Helper;
use App\models\GuestModel;
use PDOException;

class GuestController {
	public function index() {
		Helper::renderPage('/guest/index.php');
	}

	public function getMarks() {
		try {
			$telephone = $_POST['parent_phone_number'];
			$guestModel = new GuestModel();
			$result = $guestModel->getMarkTableByParentTelephone($telephone);

			if(count($result) === 0) {
				Helper::redirectTo('/guest', [
					'status' => 'danger',
					'message' => 'Lấy dữ liệu bảng điẻm học sinh thất bại.'
				]);
			}

			$student = [];
			$class = [];
			$teacher = [];
			$marks = [];

			foreach($result[0] as $key => $value) {
				if(str_starts_with($key, 'student_')) {
					$student[substr($key, 8)] = $value;
				}
				if(str_starts_with($key, 'class_')) {
					$class[substr($key, 6)] = $value;
				}
				if(str_starts_with($key, 'teacher_')) {
					$teacher[substr($key, 8)] = $value;
				}
			}
			foreach($result as $key => $value) {
				$mark = [];
				foreach($value as $k => $v) {
					if(!str_starts_with($k, 'student_') && !str_starts_with($k, 'class_') && !str_starts_with($k, 'teacher_')) {
						$mark[$k] = $v;
					}
				}
				$marks[] = $mark;
			}

			$markTable = [
				'student' => $student,
				'class' => $class,
				'teacher' => $teacher,
				'marks' => $marks
			];

			Helper::redirectTo('/guest', [
				'markTable' => $markTable
			]);
		}
		catch(PDOException $e) {
			Helper::redirectTo('/guest', [
				'status' => 'danger',
				'message' => 'Lấy dữ liệu bảng điẻm học sinh thất bại.'
			]);
		}
	}
}
