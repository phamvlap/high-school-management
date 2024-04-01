use high_school_management;
delimiter $$

-- [procedure]: add_student(full_name, date_of_birth, address, parent_phone_number, class_id)
-- [author]: camtu
create procedure add_student	(
	in _student_id int,
	in _full_name varchar(255),
	in _date_of_birth date,
	in _address varchar(255),
	in _parent_phone_number char(10),
	in _class_id int,
	in _academic_year char(9)
)
begin
	if exists (
		select 1
		from academic_years as years
		where years.academic_year = _academic_year
	) 
    then
		if exists (
			select 1
			from classes
			where classes.class_id = _class_id
		) 
		then
			if (_student_id = -1)
			then
				insert into students (full_name, date_of_birth, address, parent_phone_number, class_id) 
					values (_full_name, _date_of_birth, _address, _parent_phone_number, _class_id);
			else
				update students 
                set full_name = _full_name, 
					date_of_birth = date_of_birth, 
					address = _address, 
					parent_phone_number = _parent_phone_number, 
					class_id = _class_id
				where students.student_id = _student_id;
			end if;
		end if;
	end if;
end $$

-- [excample]: call add_student(1, 'Nguyen Van A', '2000-01-01', 'Ha Noi', '0123456789', 1, '2020-2021');

-- [procedure]: delete_student(student_id)
-- [author]: camtu
drop procedure if exists delete_student $$
create procedure delete_student	(
	in _student_id int
)
begin
    delete from students 
	where student_id = _student_id;
end $$
-- [excample]: call delete_student(1);

-- [procedure]: delete_student(student_id)
-- [author]: camtu
drop procedure if exists delete_student $$
create procedure delete_student	(
	in _student_id int
)
begin
    delete from students 
	where student_id = _student_id;
end $$
-- [excample]: call delete_student(1);

-- [procedure]: get_student_by_id(student_id)
-- [author]: camtu
drop procedure if exists get_student_by_id $$
create procedure get_student_by_id (
	in _student_id int
)
begin
	select *
	from students 
	where student_id = _student_id;
end $$

-- [excample]: call get_student_by_id(1);

-- [procedure]: get_all_students(_address, _class_id, _academic_year)
-- [author]: camtu
delimiter $$
drop procedure if exists get_all_students $$
create procedure get_all_students   ( 
	in  _address varchar(255),
	in _class_id int,
	in _academic_year char(9),
	in _is_sort_by_name_desc tinyint,
	in _limit int,
	in _offset int
)
begin
    select * 
	from students 
		join classes on students.class_id = classes.class_id
	where (_address is null or address like concat('%', concat( _address, '%'))) 
		  and (_class_id is null or students.class_id = _class_id)
		  and (_academic_year is null or academic_year like concat('%', concat( _academic_year, '%')))
	order by 
		case when _is_sort_by_name_desc = 1 then full_name end desc,
		case when _is_sort_by_name_desc = 0 then full_name end asc
	limit _limit
	offset _offset;;
end $$

-- [excample]: call get_all_students('Ha Noi','2',null);

-- ----------------------------------------------------------------------------------------------------------------------------

-- [procedure]: delete_all_students()
-- [author]: camtu
drop procedure if exists delete_all_students $$
create procedure delete_all_students ()
begin
    delete from students;
end $$

-- [excample]: call delete_all_students();

-- [function]: get_total_students()
-- [author]: camtu
drop function if exists get_total_students $$
create function get_total_students (
	_address varchar(255),
    _class_id int,
    _academic_year char(9)
)
begin
    declare total_students int;

	select count(*) into total_students
    from students
	where (_address is null or address like concat('%', _address, '%')) 
		and (_class_id is null or type like class_id = _class_id)
		and (_academic_year is null or address like concat('%', _academic_year, '%')) 
	limit _limit 
    offset _offset;

    return total_students;
end $$
