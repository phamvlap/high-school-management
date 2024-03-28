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
	insert into room_class(room_id, class_id, semester)
		values (_room_id, _class_id, _semester);
end $$
-- [example]: call add_room_class('105', 102, 1);

-- [procedure]: delete_room(_room_id, _class_id, _semester)
-- [author]: tronghuu
drop procedure if exists delete_room_class $$
create procedure delete_room_class(
	in _room_id int, 
	in _class_id int, 
	in _semester tinyint
)
begin 
	delete from room_class 
	where 
		room_id = _room_id 
		and class_id = _class_id 
		and semester = _semester;
end $$
-- [example]: call delete_room_class(3, 1, 1);

-- [procedure]: update_room_class(_room_id, _class_id, _semester, _new_room_id, _new_class_id, _new_semester)
-- [author]: tronghuu
drop procedure if exists update_room_class $$
create procedure update_room_class(
	in _room_id int, 
	in _class_id int, 
	in _semester tinyint, 
	in _new_room_id int, 
	in _new_class_id int, 
	in _new_semester tinyint
)
begin 
	call add_room_class(_new_room_id, _new_class_id, _new_semester);
	call delete_room_class(_room_id, _class_id, _semester);
end $$
-- [example]:
-- call update_room_class(1, 1, 1, 2, 2, 2);
