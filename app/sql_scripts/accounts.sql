delimiter $$

-- [procedure]: add_account(username, password, type)
-- [author]: tronghuu
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
-- [example]: call add_account('admin', 'admin', 'admin');

-- [procedure]: delete_account(username)
-- [author]: tronghuu
create procedure delete_account(
    in _username varchar(50)
)
begin 
    delete from accounts 
    where username = _username;
end $$
-- [example]: call delete_account('admin');

-- [procedure]: get_account_by_username(username)
-- [author]: camtu
create procedure get_account_by_username(
    in _username varchar(50)
)
begin 
    (select username, type from accounts where username = _username);
end $$

delimiter $$
drop procedure if exists get_all_accounts $$
create procedure get_all_accounts(
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
	limit _limit offset _offset;
end $$
-- -- [example]: call get_all_accounts();use high_school_management;
delimiter $$