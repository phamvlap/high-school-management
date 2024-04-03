delimiter $$
-- [procedure]:	add_mark(_student_id, _subject_id, _semester, _oral_score, __15_minutes_score, __1_period_score, _semester_score)
-- [example]: call add_mark(1, 2, 1, 3, 4, 5, 6);
drop procedure if exists add_mark $$
create procedure add_mark (	
	in _student_id int,
	in _subject_id int,
	in _semester tinyint, 
    in _oral_score decimal(4, 2), 
    in __15_minutes_score decimal(4, 2), 
    in __1_period_score decimal(4, 2),
    in _semester_score decimal(4, 2)
)
begin
    if exists (
        select *
        from marks
        where student_id = _student_id
            and subject_id = _subject_id
            and semester = _semester
    ) 
    then
        update marks
        set 
            oral_score = _oral_score,
            _15_minutes_score = __15_minutes_score,
            _1_period_score = __1_period_score,
            semester_score = _semester_score
        where 
            student_id = _student_id 
            and subject_id = _subject_id 
            and semester = _semester;
    else
        insert into marks(student_id, subject_id, semester, oral_score, _15_minutes_score, _1_period_score, semester_score)
            values(_student_id, _subject_id, _semester, _oral_score, __15_minutes_score, __1_period_score, _semester_score);
  end if;
end $$

-- [procedure]:	delete_mark(_student_id, _subject_id, _semester)
-- [example]: call delete_mark(1, 2, 1);
drop procedure if exists delete_mark $$
create procedure delete_mark (
    in _student_id int, 
    in _subject_id int,	
    in _semester tinyint
)
begin
    delete from marks 
    where student_id = _student_id 
        and subject_id = _subject_id 
        and semester = _semester;
end $$

-- [procedure]:	get_mark_by_student_id(_student_id, _subject_id, _semester)
-- [example]: call get_mark_by_student_id(1, 2, 1);
drop procedure if exists get_all_marks $$
create procedure get_all_marks (
    in _student_id int,
    in _subject_id int,
    in _semester tinyint,
    in _limit int,
    in _offset int
)
begin
    select * 
    from marks as m
        join students as s on m.student_id = s.student_id
        join subjects as sb on m.subject_id = sb.subject_id
        join classes as c on s.class_id = c.class_id
    where 
        (_student_id is null or s.student_id = _student_id)
        and (_subject_id is null or sb.subject_id = _subject_id)
        and (_semester is null or m.semester = _semester)
    limit _limit
    offset _offset;
end $$

-- [function]: get_total_marks (_student_id, _subject_id, _semester)
-- [example]: select get_total_marks (null, null, null,3,0);
drop function if exists get_total_marks $$
create function get_total_marks (
    _student_id int,
    _subject_id int,
    _semester tinyint
)
	returns int
	reads sql data
    deterministic
begin
    declare total int;
    select count(*) into total
    from marks
        join students as s on marks.student_id = s.student_id
        join subjects as sb on marks.subject_id = sb.subject_id
        join classes as c on s.class_id = c.class_id
    where 
        (_student_id is null or s.student_id = _student_id)
        and (_subject_id is null or sb.subject_id = _subject_id)
        and (_semester is null or marks.semester = _semester);
    return total;
end $$

-- [procedure]: get_mark_table_student(student_id)
-- [example]: call get_mark_table_student('0123456789');
drop procedure if exists get_mark_table_by_parent_telephone $$
create procedure get_mark_table_by_parent_telephone (
    in _parent_phone_number char(10)
)
begin
    if exists (
        select *
        from students
        where parent_phone_number = _parent_phone_number
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
        where s.parent_phone_number = _parent_phone_number;
    end if;
end $$
