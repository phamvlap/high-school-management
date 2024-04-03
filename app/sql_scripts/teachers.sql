delimiter $$
-- [procedure]: get_all_teachers(_full_name, _address, _is_order_by_name, _limit, _offset)
-- [example]: call get_all_teachers(null, null, null, null, 1, 10, 0);
drop procedure if exists get_all_teachers $$
create procedure get_all_teachers(  
    in _full_name varchar(255),
    in _address varchar(255),
    in _is_order_by_name tinyint,
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
        case when _is_order_by_name = 1 then reverse_string(full_name) end COLLATE utf8mb4_unicode_ci asc,
        case when _is_order_by_name = 0 then reverse_string(full_name) end COLLATE utf8mb4_unicode_ci desc,
        teacher_id asc
    limit _limit
    offset _offset;
end $$

-- [function]: get_total_teachers(_full_name, _date_of_birth_start, _date_of_birth_end, _address, is_order_by_name)
-- [example]: call get_total_teachers(null, null, null, null, null);
drop function if exists get_total_teachers $$
create function get_total_teachers(    
    _full_name varchar(255),
    _address varchar(255)
)
    returns int
    reads sql data
    deterministic
begin
    declare total int;
    select count(*) into total
    from teachers  
    where 
        (_full_name is null or full_name like concat('%',_full_name,'%')) 
        and (_address is null or address like concat('%',_address,'%'));
    return total;
end $$

-- [procedure]: add_teacher(_teacher_id, _full_name, _date_of_birth, _address, _phone_number)
-- [example]: call add_teacher(-1, 'Nguyễn Văn A', '1990-01-01', 'Hà Nội', '0123456789');
drop procedure if exists add_teacher $$
create procedure add_teacher(
    in _teacher_id int, 
    in _full_name varchar(255),
    in _date_of_birth date,
    in _address varchar(255),
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

-- [procedure]: delete_teacher(_teacher_id)
-- [example]: call delete_teacher(1);
drop procedure if exists delete_teacher$$
create procedure delete_teacher (
    in _teacher_id int
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
            where teacher_id = _teacher_id;

            delete from teachers 
            where teacher_id = _teacher_id;
        commit;
    end;
end $$
