delimiter $$

-- [procedure]: add_student(_student_id, _full_name, _date_of_birth, _address, _parent_phone_number, _class_id)
-- [example]: call add_student(1, 'Nguyễn Thị Lan', '2007-05-15', '123 ABC Street, XYZ City', '0123456789', 1);
drop procedure if exists add_student $$
create procedure add_student (
	in _student_id int,
	in _full_name varchar(255),
	in _date_of_birth date,
	in _address varchar(255),
	in _parent_phone_number char(10),
	in _class_id int
)
begin
	if (_student_id = -1)
	then
		insert into students (full_name, date_of_birth, address, parent_phone_number, class_id) 
			values (_full_name, _date_of_birth, _address, _parent_phone_number, _class_id);
	else
		update students 
		set full_name = _full_name, 
			date_of_birth = _date_of_birth, 
			address = _address, 
			parent_phone_number = _parent_phone_number, 
			class_id = _class_id
		where student_id = _student_id;
	end if;
end $$

-- [procedure]: delete_student(student_id)
-- [example]: call delete_student(1);
delimiter $$
drop procedure if exists delete_student $$
create procedure delete_student	(
	in _student_id int
)
begin
    delete from students 
	where student_id = _student_id;
end $$

-- [procedure]: get_student_by_id(student_id)
-- [example]: call get_student_by_id(1);
drop procedure if exists get_student_by_id $$
create procedure get_student_by_id (
	in _student_id int
)
begin
	select *
	from students 
	where student_id = _student_id;
end $$

-- [procedure]: get_all_students(_address, _class_id, _academic_year)
-- [example]: call get_all_students('123 ABC Street, XYZ City', 1, '2023-2024', 0, 10, 0);
drop procedure if exists get_all_students $$
create procedure get_all_students   (
	in _full_name varchar(50),
	in _address varchar(255),
	in _class_id int,
	in _academic_year char(9),
	in _is_order_by_name tinyint,
    in _limit int,
    in _offset int
)
begin
    select * 
	from students 
		join classes on students.class_id = classes.class_id
	 where (_full_name is null or full_name like concat('%', _full_name, '%'))
          and (_address is null or address like concat('%', _address, '%'))
          and (_class_id is null or students.class_id = _class_id)
          and (_academic_year is null or academic_year like concat('%', _academic_year, '%'))
	order by 
		case when _is_order_by_name = 1 then reverse_string(full_name) end COLLATE utf8mb4_unicode_ci asc,
		case when _is_order_by_name = 0 then reverse_string(full_name) end COLLATE utf8mb4_unicode_ci desc,
		student_id asc
	limit _limit
    offset _offset;
end $$

-- [function]: get_total_students(_full_name, _address, _class_id, _academic_year)
-- [example]: select get_total_students(null,1,null);
drop function if exists get_total_students $$
create function get_total_students(
	_full_name varchar(50),
	_address varchar(255), 
    _class_id int,
    _academic_year char(9)
)
	returns int
	reads sql data
	deterministic
begin
	declare total_students int;
    
    select count(*) into total_students
    from students
		join classes on students.class_id = classes.class_id
     where (_full_name is null or full_name like concat('%', _full_name, '%'))
        and (_address is null or address like concat('%', _address, '%'))
        and (_class_id is null or students.class_id = _class_id)
        and (_academic_year is null or academic_year like concat('%', _academic_year, '%'));
	return total_students;
end $$
