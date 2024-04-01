delimiter $$

-- [procedure]:	add_teacher(_teacher_id, _full_name, _date_of_birth, _address, _phone_number)
-- [author]: cuong
drop procedure if exists add_teacher$$
create procedure add_teacher(
    in _teacher_id int, 
	in _full_name varchar(255),
	in _date_of_birth dat
    in _address varchar(255),e,
    in _phone_number varchar(20)
)
begin
    if (_teacher_id = -1) then
        insert into teachers(full_name, date_of_birth, address, phone_number)
            values(_full_name, _date_of_birth, _address, _phone_number);
    else
        update teachers
        set 
            full_name = _full_name,
            date_of_birth = _date_of_birth,
            address = _address,
            phone_number = _phone_number
            where teacher_id = _teacher_id;
    end if;
end $$
-- [example]: call add_teacher(-1, 'Nguyen Van A', '1990-01-01', 'Ha Noi', '0123456789');

-- [procedure]:	delete_teacher(_teacher_id)
-- [author]: cuong
drop procedure if exists delete_teacher$$
create procedure delete_teacher(
    in _teacher_id int
)
begin
    delete from teaching
    where teacher_id = _teacher_id;

    delete from homeroom_teachers 
    where teacher_id = _teacher_id;

    delete from teachers 
    where teacher_id = _teacher_id;
end $$
-- [example]: call delete_teacher(1);

-- [procedure]:	get_teacher_by_id(_teacher_id)
-- [author]: cuong
drop procedure if exists get_teacher_by_id$$
create procedure get_teacher_by_id(
    in _teacher_id int
)
begin
    select * 
    from teachers 
    where teacher_id = _teacher_id;
end $$
-- [example]: call get_teacher_by_id(1);

-- [procedure]:	get_all_teachers(_full_name, _date_of_birth_start, _date_of_birth_end, _address, is_order_by_name)
-- [author]: cuong
drop procedure if exists get_all_teachers$$
create procedure get_all_teachers(	
    in _full_name varchar(255),
	in _address varchar(255),
	in is_order_by_name tinyint,
    in _limit int,
    in _offset int
)
begin
    select * 
    from teachers 
    where 
        (_full_name is null or full_name like concat('%',_full_name,'%')) 
        and (_address is null or address like concat('%',_address,'%'))
        order by
            case when is_order_by_name = 1 then full_name end asc,
            case when is_order_by_name = 0 then full_name end desc
        limit _limit
        offset _offset;
end $$
-- [example]: call get_all_teachers(null, null, null, null, 1, 10, 0);

-- [procedure]:	get_total_teachers(_full_name, _date_of_birth_start, _date_of_birth_end, _address, is_order_by_name)
-- [author]: cuong
drop procedure if exists get_total_teachers$$
create procedure get_total_teachers(	
    in _full_name varchar(255),
    in _address varchar(255)
)
begin
    select count(*) as total
    from teachers 
    where 
        (_full_name is null or full_name like concat('%',_full_name,'%')) 
        and (_date_of_birth_start is null or date_of_birth >= _date_of_birth_start)
        and (_date_of_birth_end is null or date_of_birth <= _date_of_birth_end)
        and (_address is null or address like concat('%',_address,'%'));
end $$

