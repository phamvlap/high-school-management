use high_school_management;
delimiter $$

-- [procedure]: add_subject(subject_id, subject_name, grade)
-- author: camtu
drop procedure if exists add_subject $$
create procedure add_subject ( 
    in _subject_id int ,
    in _subject_name varchar(255),
    in _grade tinyint
)
begin 
    insert into subjects (subject_id, subject_name, grade) 
        values (_subject_id, _subject_name, _grade);
end $$

-- [example]: call add_subject(1, 'English', 1);

-- [procedure]: delete_subject(subject_id)
-- [author]: camtu

drop procedure if exists delete_subject $$
create procedure delete_subject (
    in _subject_id int
)
begin
    delete from subjects
    where subject_id = _subject_id;
end $$

-- [example]: call delete_subject(1);
-- [procedure]: update_subject(subject_id, subject_name, grade)
-- author: camtu
drop procedure if exists update_subject $$
create procedure update_subject (
    in _subject_id int, 
    in _subject_name varchar(255), 
    in _grade tinyint
)
begin
    update subjects 
    set subject_name = _subject_name, grade = _grade 
    where subject_id = _subject_id;
end $$

-- [example]: call update_subject(1, 'English', 1);

-- [procedure]: get_subject_by_id(subject_id)
-- author: camtu
drop procedure if exists get_subject_by_id $$
create procedure get_subject_by_id (
    in _subject_id int
)
begin
    select * 
    from subjects 
    where subject_id = _subject_id;
end $$

-- [example]: call get_subject_by_id(1);

-- [procedure]: get_all_subject(subject_name, grade)
-- author: camtu
drop procedure if exists get_all_subject $$
create procedure get_all_subject (
    in _subject_name varchar(255), 
    in _grade tinyint, 
    in _is_sort_by_grade_desc tinyint
)
begin
    select * 
    from subjects 
    where (_subject_name is null or subject_name like concat('%', concat(_subject_name, '%')))
          and (_grade is null or grade = _grade)
    order by 
        case when _is_sort_by_grade_desc = 1 then grade end desc,
        case when _is_sort_by_grade_desc = 0 then grade end asc;
end $$

-- [example]: call get_all_subject('English', 1, 1);
