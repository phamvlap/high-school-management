delimiter $$

-- [procedure]: add_room(_room_id, _room_number, _maximum_capacity)
-- [author]: tronghuu
drop procedure if exists add_room $$
create procedure add_room(
	in _room_id int, 
	in _room_number varchar(50), 
	in _maximum_capacity int
) 
begin
	if (_room_id = -1) then 
		insert into rooms(room_number, maximum_capacity)
		values (_room_number, _maximum_capacity);
	else 
		update rooms 
		set room_number = _room_number, maximum_capacity = _maximum_capacity
		where room_id = _room_id;
	end if;
end $$
-- [example]: call add_room(2, '105', 102);

-- [procedure]: delete_room(_room_id)
-- [author]: tronghuu
drop procedure if exists delete_room $$
create procedure delete_room(
	in _room_id int
) 
begin 
	delete from room_class 
	where room_id = _room_id;

	delete from rooms 
	where room_id = _room_id;
end $$
-- [example]: call delete_room(3);

-- [procedure]: get_room_by_id(_room_id)
-- [author]: tronghuu
drop procedure if exists get_room_by_id $$
create procedure get_room_by_id(
	in _room_id int
)
begin 
	(select * from rooms where room_id = _room_id);
end $$
-- [example]: call get_room_by_id(4);

-- [procedure]: get_all_rooms(_room_number, _min_capacity, _max_capacity)
-- [author]: tronghuu
delimiter $$
drop procedure if exists get_all_rooms $$
create procedure get_all_rooms(
	in _room_number varchar(50), 
    in _min_capacity int, 
    in _max_capacity int,
	in _is_sort_by_capacity tinyint
)
begin 
	select * 
	from rooms
	where 
		(_room_number is null or room_number like concat('%', _room_number, '%')) 
		and (_min_capacity is null or maximum_capacity >= _min_capacity) 
		and (_max_capacity is null or maximum_capacity <= _max_capacity)
	order by
		case when _is_sort_by_capacity = 0 then maximum_capacity end desc,
		case when _is_sort_by_capacity = 1 then maximum_capacity end asc,
		room_number asc;
end $$
-- [example]: call get_all_rooms(null, null, null, 0, 1);