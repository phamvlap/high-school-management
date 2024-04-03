delimiter $$

-- [procedure]: add_account(username, password, type)
-- [example]: call add_account('admin', 'admin', 'admin');
drop procedure if exists add_account $$
create procedure add_account(
    in _username varchar(50), 
    in _password varchar(255), 
    in _type varchar(50)
)
begin
    insert into accounts(username, password, type)
        values (_username, _password, _type);
end $$

-- [procedure]: delete_account(username)
-- [example]: call delete_account('admin');
create procedure delete_account(
    in _username varchar(50)
)
begin
    delete from accounts 
    where username = _username;
end $$

-- [procedure]: get_account_by_username(username)
-- [example]: call get_account_by_username('admin');
create procedure get_account_by_username(
    in _username varchar(50)
)
begin 
    select *
    from accounts
    where username = _username;
end $$

-- [procedure]: get_all_accounts(username, type, limit, offset)
-- [example]: call get_all_accounts();use high_school_management;
drop procedure if exists get_all_accounts $$
create procedure get_all_accounts (
	in _username varchar(50),
	in _type varchar(50),
    in _limit int,
    in _offset int
)
begin 
	select * 
	from accounts
	where (_username is null or username like concat('%', _username, '%')) 
		and (_type is null or type like concat('%', _type, '%'))
	limit _limit
    offset _offset;
end $$

-- [function]: get_total_accounts(username, type)
-- [example]: call get_total_accounts('admin', 'admin');
delimiter $$
drop function if exists get_total_accounts $$
create function get_total_accounts (
    _username varchar(50),
    _type varchar(50)
)
    returns int
    reads sql data 
    deterministic
begin 
    declare total int;
    
    select count(*) into total
    from accounts
    where (_username is null or username like concat('%', _username, '%')) 
        and (_type is null or type like concat('%', _type, '%'));
    return total;
end $$