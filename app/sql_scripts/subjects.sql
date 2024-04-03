delimiter $$

-- [procedure]: add_subject(subject_id, subject_name, grade)
-- [example]: call add_subject(1, 'English', 1);
drop procedure if exists add_subject $$
create procedure add_subject ( 
    in _subject_id int,
    in _subject_name varchar(255),
    in _grade tinyint
)
begin 
    if(_subject_id = -1)
    then
		insert into subjects (subject_name, grade)
			values (_subject_name, _grade);
	else
		update subjects
		set subject_name = _subject_name, grade = _grade
		where subject_id = _subject_id;
	end if;
end $$

-- [procedure]: delete_subject(subject_id)
-- [example]: call delete_subject(1);
drop procedure if exists delete_subject $$
create procedure delete_subject (
    in _subject_id int
)
begin
    delete from subjects
    where subject_id = _subject_id;
end $$

-- [procedure]: get_subject_by_id(subject_id)
-- [example]: call get_subject_by_id(1);
drop procedure if exists get_subject_by_id $$
create procedure get_subject_by_id (
    in _subject_id int
)
begin
    select * 
    from subjects 
    where subject_id = _subject_id;
end $$

-- [procedure]: get_all_subject(subject_name, grade)
-- [example]: call get_all_subject('Vật lí', 1, 10, 0);
drop procedure if exists get_all_subjects $$
create procedure get_all_subjects (
    in _subject_name varchar(255),
    in _grade tinyint,
    in _limit int,
    in _offset int
)
begin
    select * 
    from subjects 
    where (_subject_name is null or subject_name like concat('%', _subject_name, '%'))
        and (_grade is null or grade = _grade)
    limit _limit
    offset _offset;
end $$

-- [function] get_total_subjects(subject_name, grade)
-- [example]: call get_total_subjects('Vật lí', 1, 10, 0);
drop function if exists get_total_subjects $$
create function get_total_subjects (
    _subject_name varchar(255),
    _grade tinyint
)
	returns int
    reads sql data
    deterministic
begin
    declare total int;
    select count(*) into total 
    from subjects 
    where (_subject_name is null or subject_name like concat('%', _subject_name, '%'))
        and (_grade is null or grade = _grade);
    return total;
end $$
