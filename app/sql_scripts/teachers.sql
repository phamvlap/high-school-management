drop procedure if exists get_all_teachers$$
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
            case when _is_order_by_name = 1 then full_name end asc,
            case when _is_order_by_name = 0 then full_name end desc
        limit _limit
        offset _offset;
end $$
-- [example]: call get_all_teachers(null, null, null, null, 1, 10, 0);

-- [procedure]: get_total_teachers(_full_name, _date_of_birth_start, _date_of_birth_end, _address, is_order_by_name)
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
        and (_address is null or address like concat('%',_address,'%'));
end $$

call get_total_teachers(null, null);
