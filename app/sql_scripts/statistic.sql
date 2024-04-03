delimiter $$

-- [function]: calculate_average_mark(_student_id, _subject_id, _semester)
drop function if exists calculate_average_mark$$
create function calculate_average_mark(
    _student_id int,
    _subject_id int,
    _semester tinyint
)
returns decimal(4, 2)
reads sql data
deterministic
begin
    declare _oral_score decimal(4, 2);
    declare __15_minutes_score decimal(4, 2);
    declare __1_period_score decimal(4, 2);
    declare _semester_score decimal(4, 2);
    declare _average_score decimal(4, 2);
    select oral_score, _15_minutes_score, _1_period_score, semester_score
    into _oral_score, __15_minutes_score, __1_period_score, _semester_score
    from marks
    where student_id = _student_id and subject_id = _subject_id and semester = _semester;
    set _average_score = (_oral_score + __15_minutes_score * 1 + __1_period_score * 2 + _semester_score * 3) / 7;
    return _average_score;
end $$

-- [function]: calculate_average_mark(_student_id, _subject_id, _semester)
-- one subject
drop function if exists calculate_average_mark $$
create function calculate_average_mark(
    _student_id int,
    _subject_id int,
    _semester tinyint
)
returns decimal(4, 2)
reads sql data
deterministic
begin
    declare _oral_score decimal(4, 2);
    declare __15_minutes_score decimal(4, 2);
    declare __1_period_score decimal(4, 2);
    declare _semester_score decimal(4, 2);
    declare _average_score decimal(4, 2);
    select oral_score, _15_minutes_score, _1_period_score, semester_score
    into _oral_score, __15_minutes_score, __1_period_score, _semester_score
    from marks
    where student_id = _student_id and subject_id = _subject_id and semester = _semester;
    set _average_score = (_oral_score + __15_minutes_score * 1 + __1_period_score * 2 + _semester_score * 3) / 7;
    return _average_score;
end $$

-- [function]: get_all_mark_by_student_id(_student_id)
drop function if exists get_all_mark_by_student_id$$
create function get_all_mark_by_student_id(
    _student_id int
)
returns decimal(4, 2)
reads sql data
deterministic
begin
    declare _average_score decimal(4, 2);
    select avg(oral_score + _15_minutes_score * 1 + _1_period_score * 2 + semester_score * 3) / 7
    into _average_score
    from marks
    where student_id = _student_id;
    return _average_score;
end $$

-- [procedure]: calculate_average_mark_by_class_id_semester(_class_id, _semester)
delimiter $$
drop procedure if exists calculate_average_mark_by_class_id_semester $$
create procedure calculate_average_mark_by_class_id_semester(
	_class_id int,
    _semester tinyint
)
begin
    select s.student_id, s.full_name, 
		c.class_id, c.class_name, c.academic_year,
		sub.subject_id, sub.subject_name,
		m.oral_score, m._15_minutes_score, m._1_period_score, m.semester_score,
		(m.oral_score + m._15_minutes_score * 1 + m._1_period_score * 2 + m.semester_score * 3) / 7 as average_score,
		m.semester
    from marks as m 
		join students as s on m.student_id = s.student_id
		join classes as c on s.class_id = c.class_id
		join subjects as sub on m.subject_id = sub.subject_id
    where c.class_id = _class_id and m.semester = _semester
    order by reverse_string(full_name) COLLATE utf8mb4_unicode_ci asc;
end $$
