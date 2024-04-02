-- [procedure]: add_class(_class_id, _class_name, _academic_year)
-- [author]: phamvlap
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
		if not exists (
			select academic_year
			from academic_years
			where academic_year = _academic_year
		)
		then
			insert into academic_years(academic_year)
				values(_academic_year);
		end if;
		insert into classes(class_name, academic_year)
			values(_class_name, _academic_year);
	else
		set @old_year = (
			select academic_year
			from classes
			where class_id = _class_id
		);
		if not exists (
			select academic_year
			from academic_years
			where academic_year = _academic_year
		)
		then
			insert into academic_years(academic_year)
				values(_academic_year);
		end if;
        
		update classes
		set class_name = _class_name, academic_year = _academic_year
		where class_id = _class_id;
		if not exists (
			select *
			from classes
			where academic_year = @old_year
		)
		then
			delete from academic_years
			where academic_year = @old_year;
		end if;
	end if;
end $$

-- call add_class(-1, '10A8', '2023-2024');

-- [procedure]: delete_class(_class_id)
-- [author]: phamvlap
delimiter $$
drop procedure if exists delete_class $$
create procedure delete_class(
	in _class_id int
)
begin
	delete from homeroom_teachers
    where class_id = _class_id;
    delete from room_class
    where class_id = _class_id;
    delete from students
    where class_id = _class_id;
    delete from classes
    where class_id = _class_id;
end $$

-- [example]:
-- call delete_class(2);

-- [function]: get_all(_class_name, _grade, _academic_year)
-- [author]: phamvlap
delimiter $$

drop procedure if exists get_all_classes $$

create procedure get_all_classes(
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

-- [function]: get_all(_class_name, _grade, _academic_year)
-- [author]: phamvlap

drop function if exists get_total_classes $$

create function get_total_classes(
	_class_name varchar(10),
	_grade varchar(10),
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

delimiter $$
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

-- select get_inserted_id();