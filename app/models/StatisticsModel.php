<?php

namespace App\models;

use PDO;
use App\db\PDOFactory;

class StatisticsModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = PDOFactory::connect();
    }

    public function getCountTeachers(): int
    {
        $statement = $this->pdo->prepare("select count(*) as total_teachers from teachers");
        $statement->execute();
        return $statement->fetchColumn();
    }

    public function getCountHomeroomTeachers(array $filter): int
    {
        $statement = $this->pdo->prepare(
            "select count(distinct(t.teacher_id)) as total_teachers
            from teachers as t 
                join homeroom_teachers as h on t.teacher_id = h.teacher_id 
                join classes as c on h.class_id = c.class_id
            where (:academic_year is null or c.academic_year = :academic_year)"
        );
        $statement->bindParam(':academic_year', $filter['academic_year']);
        $statement->execute();
        return $statement->fetchColumn();
    }

    public function getCountStudents($filter): int
    {
        $statement = $this->pdo->prepare(
            "select count(distinct(s.student_id)) as total_students
            from students as s
                join classes as c on s.class_id = c.class_id
            where (:academic_year is null or c.academic_year like concat('%', :academic_year, '%'))
                and (:class_id is null or c.class_id = :class_id)
                and (:grade is null or c.class_name like concat(:grade, '%'))"
        );

        $statement->bindParam(':academic_year', $filter['academic_year']);
        $statement->bindParam(':class_id', $filter['class_id']);
        $statement->bindParam(':grade', $filter['grade']);
        $statement->execute();
        return $statement->fetchColumn();
    }

    public function calculateAverageMarkByClassIdSemester($filter): array
    {
        $statement = $this->pdo->prepare(
            "call calculate_average_mark_by_class_id_semester(:class_id,:semester)"
        );
        $statement->bindParam(':class_id', $filter['class_id']);
        $statement->bindParam(':semester', $filter['semester']);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
