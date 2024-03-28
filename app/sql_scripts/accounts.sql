delimiter $$

-- [procedure]: add_account(username, password, type)
-- [author]: tronghuu
create procedure add_account(
    in _username varchar(50), 
    in _password varchar(50), 
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
-- [author]: tronghuu
create procedure get_account_by_username(
    in _username varchar(50)
)
begin 
    (select username, type from accounts where username = _username);
end $$

-- [procedure]: get_all_accounts()
-- [author]: tronghuu
create procedure get_all_accounts()
begin 
    select username, type from accounts;
end $$
-- [example]: call get_all_accounts();