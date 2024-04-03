<?php

namespace App\controllers;

use App\models\StatisticsModel;
use App\utils\Helper;

class StatisticsController
{
    public function index()
    {
        try {
            Helper::renderPage('/statistics/index.php', []);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function teachers()
    {
        try {
            $filter = [
                'academic_year' => isset($_GET['academic_year']) ? $_GET['academic_year'] : null,
            ];

            $statisticsModel = new StatisticsModel();
            $totalTeachers = $statisticsModel->getCountTeachers();
            $totalHomeroomTeachers = $statisticsModel->getCountHomeroomTeachers($filter);
            Helper::renderPage(
                '/statistics/teachers.php',
                [
                    'totalTeachers' => $totalTeachers,
                    'totalHomeroomTeachers' => $totalHomeroomTeachers
                ]
            );
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function students()
    {
        try {
            $filter = [
                'academic_year' => !empty($_GET['academic_year']) ? $_GET['academic_year'] : null,
                'class_id' => !empty($_GET['class_id']) ? $_GET['class_id'] : null,
                'grade' => !empty($_GET['grade']) ? $_GET['grade'] : null,
            ];

            $statisticsModel = new StatisticsModel();
            $totalStudents = $statisticsModel->getCountStudents($filter);
            Helper::renderPage(
                '/statistics/students.php',
                [
                    'totalStudents' => $totalStudents
                ]
            );
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function marks()
    {
        try {
            $filter = [
                'class_id' => !empty($_GET['class_id']) ? (int)$_GET['class_id'] : null,
                'semester' => !empty($_GET['semester']) ? (int)$_GET['semester'] : null,
            ];
            $statisticsmodel = new StatisticsModel();
            $marks = $statisticsmodel->calculateAverageMarkByClassIdSemester($filter);

            if (empty($marks)) {
                Helper::renderPage('/statistics/marks.php', [
                    'marks' => [],
                    'subjects' => [],
                ]);
            }

            $marksBySubject = $this->marksBySubject($marks);

            // download data
            $downloadData = [];
            $i = 1;
            foreach ($marksBySubject['markPerStudents'] as $markPerStudent) {
                $averageScore = 0.0;
                $downloadData[] = [
                    $i++,
                    $markPerStudent['student_id'],
                    $markPerStudent['full_name'],
                    $markPerStudent['class_name'],
                    $markPerStudent['academic_year'],
                ];

                foreach ($marksBySubject['subjects'] as $subject) {
                    $downloadData[count($downloadData) - 1][] = $markPerStudent[$subject['subject_id']]['average_score'];

                    $averageScore += $markPerStudent[$subject['subject_id']]['average_score'];
                }

                $averageScore = number_format($averageScore / count($marksBySubject['subjects']), 2);
                $downloadData[count($downloadData) - 1][] = $averageScore;
            }

            $header = [
                'STT',
                'Mã số',
                'Họ tên',
                'Lớp',
                'Năm học',
            ];
            foreach ($marksBySubject['subjects'] as $subject) {
                $header[] = $subject['subject_name'];
            }
            $header[] = 'TBHK';

            Helper::setIntoSession('download_data', [
                'title' => 'DANH SÁCH ĐIỂM TRUNG BÌNH CỦA HỌC SINH THEO MÔN HỌC LỚP ' . $markPerStudent['class_name'] . ' HỌC KỲ ' . $filter['semester'] . ' NĂM HỌC ' . $markPerStudent['academic_year'],
                'header' => $header,
                'data' => $downloadData,
            ]);

            Helper::renderPage('/statistics/marks.php', [
                'marks' => $marksBySubject['markPerStudents'],
                'subjects' => $marksBySubject['subjects'],
            ]);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    private function marksBySubject($marks)
    {
        $subjects = [];
        foreach ($marks as $mark) {
            if (!array_key_exists($mark['subject_id'], $subjects)) {
                $subjects[$mark['subject_id']] = [
                    'subject_id' => $mark['subject_id'],
                    'subject_name' => $mark['subject_name'],
                ];
            }
        }
        $markPerStudents = [];
        foreach ($marks as $mark) {
            if (!array_key_exists($mark['student_id'], $markPerStudents)) {
                $markPerStudents[$mark['student_id']] = [
                    'student_id' => $mark['student_id'],
                    'full_name' => $mark['full_name'],
                    'class_name' => $mark['class_name'],
                    'semester' => $mark['semester'],
                    'academic_year' => $mark['academic_year'],
                ];
                foreach ($subjects as $subject) {
                    $markPerStudents[$mark['student_id']][$subject['subject_id']] = [
                        'subject_name' => $subject['subject_name'],
                        'average_score' => 0.0,
                    ];
                }
            }
            $markPerStudents[$mark['student_id']]['average_score'] = $markPerStudents[$mark['student_id']][$subject['subject_id']]['average_score'];

            $markPerStudents[$mark['student_id']][$mark['subject_id']]['average_score'] = number_format($mark['average_score'], 2);
            $markPerStudents[$mark['student_id']]['average_score'] = number_format($markPerStudents[$mark['student_id']]['average_score'], 2);
        }

        foreach ($markPerStudents as $key => $markPerStudent) {
            $markPerStudents[$key]['average_score'] = 0.0;
            foreach ($subjects as $subject) {
                $markPerStudents[$key]['average_score'] += $markPerStudent[$subject['subject_id']]['average_score'];
            }
            $markPerStudents[$key]['average_score'] = number_format($markPerStudents[$key]['average_score'] / count($subjects), 2);
        }

        return [
            'subjects' => $subjects,
            'markPerStudents' => $markPerStudents,
        ];
    }
}
