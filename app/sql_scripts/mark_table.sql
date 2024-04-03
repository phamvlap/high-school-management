-- [procedure]: get_mark_table_by_student_id(student_id)
-- [example]: call get_mark_table_by_student_id('1');
delimiter $$
drop procedure if exists get_mark_table_by_student_id $$
create procedure get_mark_table_by_student_id (
    in _student_id int
)
begin
    if exists (
        select *
        from students
        where student_id = _student_id
    )
    then
        select s.student_id as student_id,
            s.full_name as student_full_name,
            s.date_of_birth as student_date_of_birth,
            s.address as student_address,
            s.parent_phone_number as student_parent_phone_number,
            c.class_name as class_name,
            c.academic_year as class_academic_year,
            t.teacher_id as teacher_id,
            t.full_name as teacher_full_name,
            t.phone_number as teacher_phone_number,
            m.semester,
            m.oral_score as mark_oral_score,
            m._15_minutes_score as mark__15_minutes_score,
            m._1_period_score as mark__1_period_score,
            m.semester_score as mark_semester_score,
            sj.*
        from students as s join marks as m
                on s.student_id = m.student_id
            join subjects as sj
                on m.subject_id = sj.subject_id
            join classes as c
                on s.class_id = c.class_id
            join homeroom_teachers as ht
                on c.class_id = ht.class_id
            join teachers as t
                on ht.teacher_id = t.teacher_id
        where s.student_id = _student_id;
    end if;
end $$

