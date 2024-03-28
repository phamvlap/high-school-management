use high_school_management;
delimiter $$

-- [procedure]: add_teaching(teacher_id, subject_id, academic_year)
-- [author]: phamvlap
drop procedure if exists add_teaching $$
create procedure add_teaching(
	in _teacher_id int, 
	in _subject_id int,
	in _academic_year int
)
begin
	insert into teaching(teacher_id, subject_id, academic_year)
		values(_teacher_id, _subject_id, _academic_year);
end $$

-- [procedure]: delete_teaching(teacher_id, subject_id, academic_year)
-- [author]: phamvlap
drop procedure if exists delete_teaching $$
create procedure delete_teaching(
	in _teacher_id int,
	in _subject_id int,
	in _academic_year int
)
begin
	delete from teaching
	where teacher_id = _teacher_id
		and subject_id = _subject_id
		and academic_year = _academic_year;
end $$

-- [procedure]: update_teaching(teacher_id, subject_id, academic_year)
-- [author]: phamvlap
drop procedure if exists update_teaching $$
create procedure update_teaching(
	in _teacher_id int,
	in _subject_id int, 
	in _academic_year int
)
begin
	call delete_teaching(_teacher_id, _subject_id, _academic_year);
	call add_teaching(_teacher_id, _subject_id, _academic_year);
end $$