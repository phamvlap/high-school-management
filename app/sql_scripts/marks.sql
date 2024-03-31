delimiter $$

-- [procedure]:	update_mark(_student_id, _subject_id, _semester, _oral_score, __15_minutes_score, __1_period_score, _semester_score)
-- [author]: cuong
create procedure add_mark(	
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
        select * from marks
        where student_id = _student_id and subject_id = _subject_id and semester = _semester
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
-- [example]: call add_mark(1, 2, 1, 3, 4, 5, 6);

-- [procedure]:	delete_mark(_student_id, _subject_id, _semester)
-- [author]: cuong
drop procedure if exists detete_mark$$
create procedure delete_mark(
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
-- [example]: call delete_mark(1, 2, 1);

-- [procedure]:	get_mark_by_student_id(_student_id, _subject_id, _semester)
-- [author]: cuong
drop procedure if exists get_all_mark$$
create procedure get_all_mark(
    in _student_id int,
    in _subject_id int,
    in _semester tinyint
)
begin
    select * 
    from marks as m
        join students as s on m.student_id = s.student_id
        join subjects as sb on m.subject_id = sb.subject_id
    where 
        (_student_id is null or s.student_id = _student_id)
        and (_subject_id is null or sb.subject_id = _subject_id)
        and (_semester is null or m.semester = _semester);
end $$
-- [example]: call get_all_mark(3, null, null);