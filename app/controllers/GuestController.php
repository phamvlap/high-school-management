<?php

namespace App\controllers;

use App\utils\Helper;
use App\models\GuestModel;
use PDOException;

class GuestController
{
    public function index()
    {
        Helper::renderPage('/guest/index.php');
    }

    public function getMarks()
    {
        try {
            $student_id = $_POST['student_id'];
            $guestModel = new GuestModel();
            $result = $guestModel->getMarkTableByStudentID($student_id);

            if (count($result) === 0) {
                Helper::redirectTo('/parents', [
                    'status' => 'danger',
                    'message' => 'Lấy dữ liệu bảng điểm học sinh thất bại.'
                ]);
            }

            $student = [];
            $class = [];
            $teacher = [];
            $marks = [];
            $students = [];
            $studentId = $result[0]['student_id'];
            foreach ($result[0] as $key => $value) {
                if (str_starts_with($key, 'student_')) {
                    $student[substr($key, 8)] = $value;
                }
                if (str_starts_with($key, 'class_')) {
                    $class[substr($key, 6)] = $value;
                }
                if (str_starts_with($key, 'teacher_')) {
                    $teacher[substr($key, 8)] = $value;
                }
            }

            foreach ($result as $key => $value) {
                if ($studentId !== $value['student_id']) {
                    $student = [
                        'info' => $student,
                        'class' => $class,
                        'teacher' => $teacher,
                        'marks' => $this->splitMarkBySemester($marks),
                    ];
                    $students[] = $student;

                    $studentId = $value['student_id'];

                    $student = [];
                    $class = [];
                    $teacher = [];
                    $marks = [];

                    foreach ($value as $k => $v) {
                        if (str_starts_with($k, 'student_')) {
                            $student[substr($k, 8)] = $v;
                        }
                        if (str_starts_with($k, 'class_')) {
                            $class[substr($k, 6)] = $v;
                        }
                        if (str_starts_with($k, 'teacher_')) {
                            $teacher[substr($k, 8)] = $v;
                        }
                    }
                }
                $mark = [];
                foreach ($value as $k => $v) {
                    if (!str_starts_with($k, 'student_') && !str_starts_with($k, 'class_') && !str_starts_with($k, 'teacher_')) {
                        $mark[$k] = $v;
                    }
                }
                $marks[] = $mark;
            }
            $student = [
                'info' => $student,
                'class' => $class,
                'teacher' => $teacher,
                'marks' => $this->splitMarkBySemester($marks),
            ];
            $students[] = $student;

            Helper::redirectTo('/parents', [
                'markTable' => $students
            ]);
        } catch (PDOException $e) {
            Helper::redirectTo('/parents', [
                'status' => 'danger',
                'message' => 'Lấy dữ liệu bảng điẻm học sinh thất bại.'
            ]);
        }
    }

    public function splitMarkBySemester($markTable)
    {
        $mark = [];
        $marks = [];
        $semesters = [];
        $semester = $markTable[0]['semester'];
        foreach ($markTable as $row) {
            if ($semester !== $row['semester']) {
                $semesters[$semester] = $marks;

                $semester = $row['semester'];
                $marks = [];
            }
            foreach ($row as $key => $value) {
                if ($key !== 'semester') {
                    $mark[$key] = $value;
                }
            }
            $marks[] = $mark;
        }
        $semesters[$semester] = $marks;

        return $semesters;
    }
}
