delimiter $$

-- [procedure]: add_room(_room_id, _room_number, _maximum_capacity)
-- [example]: call add_room(-1, '101', 100);
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

-- [procedure]: delete_room(_room_id)
-- [example]: call delete_room(3);
drop procedure if exists delete_room $$
create procedure delete_room (
	in _room_id int
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
			where room_id = _room_id;

			delete from rooms
			where room_id = _room_id;
		commit;
	end;
end $$

-- [procedure]: get_room_by_id(_room_id)
-- [example]: call get_room_by_id(4);
drop procedure if exists get_room_by_id $$
create procedure get_room_by_id (
	in _room_id int
)
begin 
	select *
	from rooms
	where room_id = _room_id;
end $$

-- [procedure]: get_all_rooms(_room_number, _min_capacity, _max_capacity)
-- [example]: call get_all_rooms(null, null, null, 0, 1);
drop procedure if exists get_all_rooms $$
create procedure get_all_rooms (
	in _room_number varchar(50),
    in _min_capacity int,
    in _max_capacity int,
	in _is_sort_by_capacity tinyint,
    in _limit int,
    in _offset int
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
		room_id asc
	limit _limit
    offset _offset;
end $$

-- [function]: get_total_rooms(_room_number, _min_capacity, _max_capacity)
-- [example]: select get_total_rooms(null, null, null);
drop function if exists get_total_rooms $$
create function get_total_rooms (
	_room_number varchar(50),
    _min_capacity int,
    _max_capacity int
)
	returns int
    reads sql data
    deterministic
begin
	declare total int;
    select count(*) into total
	from rooms
	where
		(_room_number is null or room_number like concat('%', _room_number, '%')) 
		and (_min_capacity is null or maximum_capacity >= _min_capacity) 
		and (_max_capacity is null or maximum_capacity <= _max_capacity);
	return total;
end $$
