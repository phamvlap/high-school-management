delimiter $$

-- [procedure]: add_room_class(_room_id, _class_id, _semester)
-- [author]: tronghuu
drop procedure if exists add_room_class $$
create procedure add_room_class(
	in _room_id int, 
	in _class_id int, 
	in _semester tinyint
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
		delete from room_class
			where class_id = _class_id and semester = _semester;
		insert into room_class(room_id, class_id, semester)
			values (_room_id, _class_id, _semester);
    commit;
end;
	
end $$
-- [example]: call add_room_class('105', 102, 1);

-- [procedure]: delete_room(_room_id, _class_id, _semester)
-- [author]: tronghuu
drop procedure if exists delete_room_class $$
create procedure delete_room_class(
	in _class_id int, 
	in _semester tinyint
)
begin 
	delete from room_class 
	where 
		class_id = _class_id 
		and semester = _semester;
end $$
-- [example]: call delete_room_class(3, 1, 1);

-- [procedure]: get_all_room_class(_class_id, _grade, _semester, _academic_year, _is_sort_by_classname)
-- [author]: tronghuu
drop procedure if exists get_all_room_class $$
create procedure get_all_room_class(
	in _room_id int,
	in _class_id int, 
    in _grade int,
    in _semester int,
    in _academic_year char(9),
    in _is_sort_by_classname int,
	in _limit int,
	in _offset int
)
begin 
	select *
	from room_class rc
		join rooms r on rc.room_id = r.room_id
		join classes c on rc.class_id = c.class_id
	where 
		(_room_id is null or rc.room_id = _room_id)
		and (_class_id is null or rc.class_id = _class_id)
		and (_grade is null or c.class_name like concat(_grade, '%'))
		and (_semester is null or rc.semester = _semester)
		and (_academic_year is null or c.academic_year = _academic_year)
	order by
		case when _is_sort_by_classname = 1 then c.class_name end asc,
		case when _is_sort_by_classname = 0 then c.class_name end desc,
		r.room_number asc
	limit _limit offset _offset;
end $$

-- [procedure]: get_total_room_class(_class_id, _grade, _semester, _academic_year)
-- [author]: tronghuu
drop procedure if exists get_total_room_class $$
create procedure get_total_room_class(
	in _room_id int,
	in _class_id int, 
	in _grade int,
	in _semester int,
	in _academic_year char(9)
)
begin 
	select count(*)
	from room_class rc
		join rooms r on rc.room_id = r.room_id
		join classes c on rc.class_id = c.class_id
	where 
		(_room_id is null or rc.room_id = _room_id)
		and (_class_id is null or rc.class_id = _class_id)
		and (_grade is null or c.class_name like concat(_grade, '%'))
		and (_semester is null or rc.semester = _semester)
		and (_academic_year is null or c.academic_year = _academic_year);
end $$