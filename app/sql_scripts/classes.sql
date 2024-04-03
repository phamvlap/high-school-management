-- [procedure]: add_class(_class_id, _class_name, _academic_year)
-- [example]: call add_class(-1, '10A8', '2023-2024');
delimiter $$
drop procedure if exists add_class $$
create procedure add_class(
	in _class_id int,
	in _class_name varchar(10),
	in _academic_year char(9)
)
begin
	if (_class_id = -1)
	then
		insert into classes(class_name, academic_year)
			values(_class_name, _academic_year);
	else
		update classes
		set class_name = _class_name, academic_year = _academic_year
		where class_id = _class_id;
	end if;
end $$

-- [procedure]: delete_class(_class_id)
-- [example]: call delete_class(2);
delimiter $$
drop procedure if exists delete_class $$
create procedure delete_class(
	in _class_id int
)
begin
	declare continue handler for sqlexception
	begin
		rollback;
		signal sqlstate '45000'
			set message_text = '$Thông tin không hợp lệ';
	end;

	begin
		start transaction;
			delete from homeroom_teachers
		    where class_id = _class_id;
		    
		    delete from room_class
		    where class_id = _class_id;
		    
		    delete from classes
		    where class_id = _class_id;

		    update students
		    set class_id = null
			where class_id = _class_id;
		commit;
	end;
end $$

-- [procedure]: get_all(_class_name, _grade, _academic_year)
-- [example]: call get_all('10A', '10', '2023-2024', 10, 0);
drop procedure if exists get_all_classes $$
create procedure get_all_classes (
	in _class_name varchar(10),
	in _grade varchar(10),
	in _academic_year char(9),
	in _limit int, 
	in _offset int
)
begin
	select c.class_id, c.class_name, c.academic_year, 
			t.teacher_id, t.full_name
	from classes as c
		left join homeroom_teachers as ht
			on c.class_id = ht.class_id 
		left join teachers as t
			on ht.teacher_id = t.teacher_id
	where (_class_name is null or c.class_name like concat('%', _class_name, '%'))
		and (_grade is null or c.class_name like concat(_grade, '%'))
		and (_academic_year is null or c.academic_year = _academic_year)
	limit _limit
	offset _offset;
end $$

-- [function]: get_total_classes(_class_name, _grade, _academic_year)
-- [example]: select get_total_classes('10A', '10', '2023-2024');
create function get_total_classes(
	_class_name varchar(10),
	_grade varchar(2),
	_academic_year char(9)
)
	returns int
	reads sql data
	deterministic
begin
	declare total int;
	select count(*) into total
	from classes as c
		left join homeroom_teachers as ht
			on c.class_id = ht.class_id 
		left join teachers as t
			on ht.teacher_id = t.teacher_id
	where (_class_name is null or c.class_name like concat('%', _class_name, '%'))
		and (_grade is null or c.class_name like concat(_grade, '%'))
		and (_academic_year is null or c.academic_year = _academic_year);
	return total;
end $$

-- [function]: get_inserted_id()
-- [example]: select get_inserted_id();
drop function if exists get_inserted_id $$
create function get_inserted_id(
)
	returns int
	reads sql data
    deterministic
begin
	declare selected_class_id int;
	select class_id into selected_class_id
	from classes 
    order by class_id desc
	limit 1;
	return selected_class_id;
end $$
