drop database if exists high_school_management;
create database high_school_management;

use high_school_management;

create table if not exists classes (
    class_id int primary key auto_increment,
    class_name varchar(10) not null,
    academic_year char(9) not null
);

create table if not exists teachers (
    teacher_id int primary key auto_increment,
    full_name varchar(255) not null,
    date_of_birth date not null,
    address varchar(255) not null,
    phone_number char(10) not null
);

create table if not exists students (
    student_id int primary key auto_increment,
    full_name varchar(255) not null,
    date_of_birth date not null,
    address varchar(255) not null,
    parent_phone_number char(10) not null,
    class_id int,
    foreign key (class_id) references classes(class_id)
);

create table if not exists homeroom_teachers (
    teacher_id int not null,
    class_id int not null unique,
    primary key (teacher_id, class_id),
    foreign key (teacher_id) references teachers(teacher_id),
    foreign key (class_id) references classes(class_id)
);

create table if not exists rooms (
    room_id int primary key auto_increment,
    room_number varchar(50) not null,
    maximum_capacity int not null
);

create table if not exists room_class (
    room_id int not null,
    class_id int not null,
    semester tinyint not null check (semester >= 1 and semester <= 2),
    primary key (room_id, class_id, semester),
    foreign key (room_id) references rooms(room_id),
    foreign key (class_id) references classes(class_id)
);

create table if not exists subjects (
    subject_id int primary key auto_increment,
    subject_name varchar(255) not null,
    grade tinyint not null
);

create table if not exists marks (
    student_id int not null,
    subject_id int not null,
    semester tinyint not null,
    oral_score decimal(4, 2) check (oral_score >= 0 and oral_score <= 10),
    _15_minutes_score decimal(4, 2) check (_15_minutes_score >= 0 and _15_minutes_score <= 10),
    _1_period_score decimal(4, 2) check (_1_period_score >= 0 and _1_period_score <= 10),
    semester_score decimal(4, 2) check (semester_score >= 0 and semester_score <= 10),
    primary key (student_id, subject_id, semester),
    foreign key (student_id) references students(student_id),
    foreign key (subject_id) references subjects(subject_id)
);

create table if not exists accounts (
    username varchar(50) primary key,
    password varchar(255) not null,
    type varchar(10) not null check (type in ('admin', 'teacher', 'parent'))
);

DELIMITER $$
drop function if exists reverse_string $$
CREATE FUNCTION reverse_string(str VARCHAR(255))
    RETURNS VARCHAR(100)
    reads sql data
    deterministic
BEGIN
    DECLARE result VARCHAR(255);
    DECLARE i INT DEFAULT 1;
    DECLARE len INT;
    DECLARE temp VARCHAR(255);
    DECLARE reversed VARCHAR(255) DEFAULT '';
    
    SET len = (CHAR_LENGTH(str) - CHAR_LENGTH(REPLACE(str, ' ', ''))) + 1;

    WHILE i <= len DO
        SET temp = SUBSTRING_INDEX(SUBSTRING_INDEX(str, ' ', i), ' ', -1);
        SET reversed = CONCAT(temp, ' ', reversed);
        SET i = i + 1;
    END WHILE;

    SET result = TRIM(reversed);
    RETURN result;
END $$
delimiter $$

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

-- [procedure]: add_homeroom_teacher(_teacher_id, _class_id)
-- [example]: call add_homeroom_teacher(1, 1);
drop procedure if exists add_homeroom_teacher $$
create procedure add_homeroom_teacher(
	in _teacher_id int,
	in _class_id int
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
			where class_id = _class_id;
			insert into homeroom_teachers(teacher_id, class_id)
				values(_teacher_id, _class_id);
		commit;
	end;
end $$

-- [procedure]: delete_homeroom_teacher(_teacher_id, _class_id)
-- [example]: call delete_homeroom_teacher(1, 1);
drop procedure if exists delete_homeroom_teacher $$
create procedure delete_homeroom_teacher(
	in _teacher_id int,
	in _class_id int
)
begin
	delete from homeroom_teachers
	where teacher_id = _teacher_id
		and class_id = _class_id;
end $$
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
delimiter $$

-- [procedure]: add_subject(subject_id, subject_name, grade)
-- [example]: call add_subject(1, 'English', 1);
drop procedure if exists add_subject $$
create procedure add_subject ( 
    in _subject_id int,
    in _subject_name varchar(255),
    in _grade tinyint
)
begin 
    if(_subject_id = -1)
    then
		insert into subjects (subject_name, grade)
			values (_subject_name, _grade);
	else
		update subjects
		set subject_name = _subject_name, grade = _grade
		where subject_id = _subject_id;
	end if;
end $$

-- [procedure]: delete_subject(subject_id)
-- [example]: call delete_subject(1);
drop procedure if exists delete_subject $$
create procedure delete_subject (
    in _subject_id int
)
begin
    delete from subjects
    where subject_id = _subject_id;
end $$

-- [procedure]: get_subject_by_id(subject_id)
-- [example]: call get_subject_by_id(1);
drop procedure if exists get_subject_by_id $$
create procedure get_subject_by_id (
    in _subject_id int
)
begin
    select * 
    from subjects 
    where subject_id = _subject_id;
end $$

-- [procedure]: get_all_subject(subject_name, grade)
-- [example]: call get_all_subject('Vật lí', 1, 10, 0);
drop procedure if exists get_all_subjects $$
create procedure get_all_subjects (
    in _subject_name varchar(255),
    in _grade tinyint,
    in _limit int,
    in _offset int
)
begin
    select * 
    from subjects 
    where (_subject_name is null or subject_name like concat('%', _subject_name, '%'))
        and (_grade is null or grade = _grade)
    limit _limit
    offset _offset;
end $$

-- [function] get_total_subjects(subject_name, grade)
-- [example]: call get_total_subjects('Vật lí', 1, 10, 0);
drop function if exists get_total_subjects $$
create function get_total_subjects (
    _subject_name varchar(255),
    _grade tinyint
)
	returns int
    reads sql data
    deterministic
begin
    declare total int;
    select count(*) into total 
    from subjects 
    where (_subject_name is null or subject_name like concat('%', _subject_name, '%'))
        and (_grade is null or grade = _grade);
    return total;
end $$
-- [procedure]: add_class(_class_id, _class_name, _academic_year)
-- [example]: call add_class(-1, '10A8', '2023-2024');
delimiter $$
drop procedure if exists add_class $$
create procedure add_class(
	in _class_id int,
	in _class_name varchar(10),
	in _academic_year char(9)
)
begin
	if (_class_id = -1)
	then
		insert into classes(class_name, academic_year)
			values(_class_name, _academic_year);
	else
		update classes
		set class_name = _class_name, academic_year = _academic_year
		where class_id = _class_id;
	end if;
end $$

-- [procedure]: delete_class(_class_id)
-- [example]: call delete_class(2);
delimiter $$
drop procedure if exists delete_class $$
create procedure delete_class(
	in _class_id int
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
		    where class_id = _class_id;
		    
		    delete from room_class
		    where class_id = _class_id;
		    
		    delete from classes
		    where class_id = _class_id;

		    update students
		    set class_id = null
			where class_id = _class_id;
		commit;
	end;
end $$

-- [procedure]: get_all(_class_name, _grade, _academic_year)
-- [example]: call get_all('10A', '10', '2023-2024', 10, 0);
drop procedure if exists get_all_classes $$
create procedure get_all_classes (
	in _class_name varchar(10),
	in _grade varchar(10),
	in _academic_year char(9),
	in _limit int, 
	in _offset int
)
begin
	select c.class_id, c.class_name, c.academic_year, 
			t.teacher_id, t.full_name
	from classes as c
		left join homeroom_teachers as ht
			on c.class_id = ht.class_id 
		left join teachers as t
			on ht.teacher_id = t.teacher_id
	where (_class_name is null or c.class_name like concat('%', _class_name, '%'))
		and (_grade is null or c.class_name like concat(_grade, '%'))
		and (_academic_year is null or c.academic_year = _academic_year)
	limit _limit
	offset _offset;
end $$

-- [function]: get_total_classes(_class_name, _grade, _academic_year)
-- [example]: select get_total_classes('10A', '10', '2023-2024');
create function get_total_classes(
	_class_name varchar(10),
	_grade varchar(2),
	_academic_year char(9)
)
	returns int
	reads sql data
	deterministic
begin
	declare total int;
	select count(*) into total
	from classes as c
		left join homeroom_teachers as ht
			on c.class_id = ht.class_id 
		left join teachers as t
			on ht.teacher_id = t.teacher_id
	where (_class_name is null or c.class_name like concat('%', _class_name, '%'))
		and (_grade is null or c.class_name like concat(_grade, '%'))
		and (_academic_year is null or c.academic_year = _academic_year);
	return total;
end $$

-- [function]: get_inserted_id()
-- [example]: select get_inserted_id();
drop function if exists get_inserted_id $$
create function get_inserted_id(
)
	returns int
	reads sql data
    deterministic
begin
	declare selected_class_id int;
	select class_id into selected_class_id
	from classes 
    order by class_id desc
	limit 1;
	return selected_class_id;
end $$

-- trigger for classes (before insert)
drop trigger if exists before_insert_classes $$
create trigger before_insert_classes
before insert on classes
for each row
begin
    declare _new_class_name varchar(10);
    declare _new_academic_year char(9);

    set _new_class_name = new.class_name;
    set _new_academic_year = new.academic_year;

    if exists (
        select *
        from classes
        where class_name = _new_class_name
            and academic_year = _new_academic_year
    )
    then
        signal sqlstate '45000'
            set message_text = '$Thông tin không hợp lệ';
    end if;
end $$


delimiter $$
-- [procedure]:	add_mark(_student_id, _subject_id, _semester, _oral_score, __15_minutes_score, __1_period_score, _semester_score)
-- [example]: call add_mark(1, 2, 1, 3, 4, 5, 6);
drop procedure if exists add_mark $$
create procedure add_mark (	
	in _student_id int,
	in _subject_id int,
	in _semester tinyint, 
    in _oral_score decimal(4, 2), 
    in __15_minutes_score decimal(4, 2), 
    in __1_period_score decimal(4, 2),
    in _semester_score decimal(4, 2)
)
begin
    if exists (
        select *
        from marks
        where student_id = _student_id
            and subject_id = _subject_id
            and semester = _semester
    ) 
    then
        update marks
        set 
            oral_score = _oral_score,
            _15_minutes_score = __15_minutes_score,
            _1_period_score = __1_period_score,
            semester_score = _semester_score
        where 
            student_id = _student_id 
            and subject_id = _subject_id 
            and semester = _semester;
    else
        insert into marks(student_id, subject_id, semester, oral_score, _15_minutes_score, _1_period_score, semester_score)
            values(_student_id, _subject_id, _semester, _oral_score, __15_minutes_score, __1_period_score, _semester_score);
  end if;
end $$

-- [procedure]:	delete_mark(_student_id, _subject_id, _semester)
-- [example]: call delete_mark(1, 2, 1);
drop procedure if exists delete_mark $$
create procedure delete_mark (
    in _student_id int, 
    in _subject_id int,	
    in _semester tinyint
)
begin
    delete from marks 
    where student_id = _student_id 
        and subject_id = _subject_id 
        and semester = _semester;
end $$

-- [procedure]:	get_mark_by_student_id(_student_id, _subject_id, _semester)
-- [example]: call get_mark_by_student_id(1, 2, 1);
drop procedure if exists get_all_marks $$
create procedure get_all_marks (
    in _student_id int,
    in _subject_id int,
    in _semester tinyint,
    in _limit int,
    in _offset int
)
begin
    select * 
    from marks as m
        join students as s on m.student_id = s.student_id
        join subjects as sb on m.subject_id = sb.subject_id
        join classes as c on s.class_id = c.class_id
    where 
        (_student_id is null or s.student_id = _student_id)
        and (_subject_id is null or sb.subject_id = _subject_id)
        and (_semester is null or m.semester = _semester)
    limit _limit
    offset _offset;
end $$

-- [function]: get_total_marks (_student_id, _subject_id, _semester)
-- [example]: select get_total_marks (null, null, null,3,0);
drop function if exists get_total_marks $$
create function get_total_marks (
    _student_id int,
    _subject_id int,
    _semester tinyint
)
	returns int
	reads sql data
    deterministic
begin
    declare total int;
    select count(*) into total
    from marks
        join students as s on marks.student_id = s.student_id
        join subjects as sb on marks.subject_id = sb.subject_id
        join classes as c on s.class_id = c.class_id
    where 
        (_student_id is null or s.student_id = _student_id)
        and (_subject_id is null or sb.subject_id = _subject_id)
        and (_semester is null or marks.semester = _semester);
    return total;
end $$

-- [procedure]: get_mark_table_student(student_id)
-- [example]: call get_mark_table_student('0123456789');
drop procedure if exists get_mark_table_by_parent_telephone $$
create procedure get_mark_table_by_parent_telephone (
    in _parent_phone_number char(10)
)
begin
    if exists (
        select *
        from students
        where parent_phone_number = _parent_phone_number
    )
    then
        select s.student_id as student_id,
            s.full_name as student_full_name,
            s.date_of_birth as student_date_of_birth,
            s.address as student_address,
            s.parent_phone_number as student_parent_phone_number,
            c.class_name as class_name,
            c.academic_year as class_academic_year,
            t.teacher_id as teacher_id,
            t.full_name as teacher_full_name,
            t.phone_number as teacher_phone_number,
            m.semester,
            m.oral_score as mark_oral_score,
            m._15_minutes_score as mark__15_minutes_score,
            m._1_period_score as mark__1_period_score,
            m.semester_score as mark_semester_score,
            sj.*
        from students as s join marks as m
                on s.student_id = m.student_id
            join subjects as sj
                on m.subject_id = sj.subject_id
            join classes as c
                on s.class_id = c.class_id
            join homeroom_teachers as ht
                on c.class_id = ht.class_id
            join teachers as t
                on ht.teacher_id = t.teacher_id
        where s.parent_phone_number = _parent_phone_number;
    end if;
end $$

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
delimiter $$

-- [procedure]: add_room_class(_room_id, _class_id, _semester)
-- [example]: call add_room_class('105', 102, 1);
drop procedure if exists add_room_class $$
create procedure add_room_class (
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

-- [procedure]: delete_room(_room_id, _class_id, _semester)
-- [example]: call delete_room_class(3, 1, 1);
drop procedure if exists delete_room_class $$
create procedure delete_room_class (
	in _class_id int, 
	in _semester tinyint
)
begin 
	delete from room_class 
	where class_id = _class_id 
		and semester = _semester;
end $$

-- [procedure]: get_all_room_class(_class_id, _grade, _semester, _academic_year, _is_sort_by_classname)
-- [example]: call get_all_room_class(null, null, null, null, null, null, null, null);
drop procedure if exists get_all_room_class $$
create procedure get_all_room_class (
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
	limit _limit
	offset _offset;
end $$

-- [function]: get_total_room_class(_class_id, _grade, _semester, _academic_year)
-- [example]: call get_total_room_class(null, null, null, null);
drop function if exists get_total_room_class $$
create function get_total_room_class(
	_room_id int,
	_class_id int, 
	_grade int,
	_semester int,
	_academic_year char(9)
)
	returns int
	reads sql data
	deterministic
begin
	declare total int;
	select count(*) into total
	from room_class rc
		join rooms r on rc.room_id = r.room_id
		join classes c on rc.class_id = c.class_id
	where 
		(_room_id is null or rc.room_id = _room_id)
		and (_class_id is null or rc.class_id = _class_id)
		and (_grade is null or c.class_name like concat(_grade, '%'))
		and (_semester is null or rc.semester = _semester)
		and (_academic_year is null or c.academic_year = _academic_year);
	return total;
end $$
delimiter $$

-- trigger for room_class (before insert)
drop trigger if exists before_insert_room_class $$
create trigger before_insert_room_class
before insert on room_class
for each row
begin   
    declare _new_academic_year char(9);

    set _new_academic_year = (select academic_year from classes where class_id = new.class_id);

    if exists (
        select *
        from room_class as rc join classes as c on rc.class_id = c.class_id
        where room_id = new.room_id
            and semester = new.semester
            and academic_year = _new_academic_year
    )
    then
        signal sqlstate '45000'
            set message_text = '$Thông tin không hợp lệ';
    end if;
end $$

-- [procedure]: add_student(_student_id, _full_name, _date_of_birth, _address, _parent_phone_number, _class_id)
-- [example]: call add_student(1, 'Nguyễn Thị Lan', '2007-05-15', '123 ABC Street, XYZ City', '0123456789', 1);
drop procedure if exists add_student $$
create procedure add_student (
	in _student_id int,
	in _full_name varchar(255),
	in _date_of_birth date,
	in _address varchar(255),
	in _parent_phone_number char(10),
	in _class_id int
)
begin
	if (_student_id = -1)
	then
		insert into students (full_name, date_of_birth, address, parent_phone_number, class_id) 
			values (_full_name, _date_of_birth, _address, _parent_phone_number, _class_id);
	else
		update students 
		set full_name = _full_name, 
			date_of_birth = _date_of_birth, 
			address = _address, 
			parent_phone_number = _parent_phone_number, 
			class_id = _class_id
		where student_id = _student_id;
	end if;
end $$

-- [procedure]: delete_student(student_id)
-- [example]: call delete_student(1);
delimiter $$
drop procedure if exists delete_student $$
create procedure delete_student	(
	in _student_id int
)
begin
    delete from students 
	where student_id = _student_id;
end $$

-- [procedure]: get_student_by_id(student_id)
-- [example]: call get_student_by_id(1);
drop procedure if exists get_student_by_id $$
create procedure get_student_by_id (
	in _student_id int
)
begin
	select *
	from students 
	where student_id = _student_id;
end $$

-- [procedure]: get_all_students(_address, _class_id, _academic_year)
-- [example]: call get_all_students('123 ABC Street, XYZ City', 1, '2023-2024', 0, 10, 0);
drop procedure if exists get_all_students $$
create procedure get_all_students   (
	in _full_name varchar(50),
	in _address varchar(255),
	in _class_id int,
	in _academic_year char(9),
	in _is_order_by_name tinyint,
    in _limit int,
    in _offset int
)
begin
    select * 
	from students 
		join classes on students.class_id = classes.class_id
	 where (_full_name is null or full_name like concat('%', _full_name, '%'))
          and (_address is null or address like concat('%', _address, '%'))
          and (_class_id is null or students.class_id = _class_id)
          and (_academic_year is null or academic_year like concat('%', _academic_year, '%'))
	order by 
		case when _is_order_by_name = 1 then reverse_string(full_name) end COLLATE utf8mb4_unicode_ci asc,
		case when _is_order_by_name = 0 then reverse_string(full_name) end COLLATE utf8mb4_unicode_ci desc,
		student_id asc
	limit _limit
    offset _offset;
end $$

-- [function]: get_total_students(_full_name, _address, _class_id, _academic_year)
-- [example]: select get_total_students(null,1,null);
drop function if exists get_total_students $$
create function get_total_students(
	_full_name varchar(50),
	_address varchar(255), 
    _class_id int,
    _academic_year char(9)
)
	returns int
	reads sql data
	deterministic
begin
	declare total_students int;
    
    select count(*) into total_students
    from students
		join classes on students.class_id = classes.class_id
     where (_full_name is null or full_name like concat('%', _full_name, '%'))
        and (_address is null or address like concat('%', _address, '%'))
        and (_class_id is null or students.class_id = _class_id)
        and (_academic_year is null or academic_year like concat('%', _academic_year, '%'));
	return total_students;
end $$


delimiter $$

-- [function]: calculate_average_mark(_student_id, _subject_id, _semester)
drop function if exists calculate_average_mark$$
create function calculate_average_mark(
    _student_id int,
    _subject_id int,
    _semester tinyint
)
returns decimal(4, 2)
reads sql data
deterministic
begin
    declare _oral_score decimal(4, 2);
    declare __15_minutes_score decimal(4, 2);
    declare __1_period_score decimal(4, 2);
    declare _semester_score decimal(4, 2);
    declare _average_score decimal(4, 2);
    select oral_score, _15_minutes_score, _1_period_score, semester_score
    into _oral_score, __15_minutes_score, __1_period_score, _semester_score
    from marks
    where student_id = _student_id and subject_id = _subject_id and semester = _semester;
    set _average_score = (_oral_score + __15_minutes_score * 1 + __1_period_score * 2 + _semester_score * 3) / 7;
    return _average_score;
end $$

-- [function]: calculate_average_mark(_student_id, _subject_id, _semester)
-- one subject
drop function if exists calculate_average_mark $$
create function calculate_average_mark(
    _student_id int,
    _subject_id int,
    _semester tinyint
)
returns decimal(4, 2)
reads sql data
deterministic
begin
    declare _oral_score decimal(4, 2);
    declare __15_minutes_score decimal(4, 2);
    declare __1_period_score decimal(4, 2);
    declare _semester_score decimal(4, 2);
    declare _average_score decimal(4, 2);
    select oral_score, _15_minutes_score, _1_period_score, semester_score
    into _oral_score, __15_minutes_score, __1_period_score, _semester_score
    from marks
    where student_id = _student_id and subject_id = _subject_id and semester = _semester;
    set _average_score = (_oral_score + __15_minutes_score * 1 + __1_period_score * 2 + _semester_score * 3) / 7;
    return _average_score;
end $$

-- [function]: get_all_mark_by_student_id(_student_id)
drop function if exists get_all_mark_by_student_id$$
create function get_all_mark_by_student_id(
    _student_id int
)
returns decimal(4, 2)
reads sql data
deterministic
begin
    declare _average_score decimal(4, 2);
    select avg(oral_score + _15_minutes_score * 1 + _1_period_score * 2 + semester_score * 3) / 7
    into _average_score
    from marks
    where student_id = _student_id;
    return _average_score;
end $$

-- [procedure]: calculate_average_mark_by_class_id_semester(_class_id, _semester)
delimiter $$
drop procedure if exists calculate_average_mark_by_class_id_semester $$
create procedure calculate_average_mark_by_class_id_semester(
	_class_id int,
    _semester tinyint
)
begin
    select s.student_id, s.full_name, 
		c.class_id, c.class_name, c.academic_year,
		sub.subject_id, sub.subject_name,
		m.oral_score, m._15_minutes_score, m._1_period_score, m.semester_score,
		(m.oral_score + m._15_minutes_score * 1 + m._1_period_score * 2 + m.semester_score * 3) / 7 as average_score,
		m.semester
    from marks as m 
		join students as s on m.student_id = s.student_id
		join classes as c on s.class_id = c.class_id
		join subjects as sub on m.subject_id = sub.subject_id
    where c.class_id = _class_id and m.semester = _semester
    order by reverse_string(full_name) COLLATE utf8mb4_unicode_ci asc;
end $$


-- [procedure]: get_mark_table_by_student_id(student_id)
-- [example]: call get_mark_table_by_student_id('1');
delimiter $$
drop procedure if exists get_mark_table_by_student_id $$
create procedure get_mark_table_by_student_id (
    in _student_id int
)
begin
    if exists (
        select *
        from students
        where student_id = _student_id
    )
    then
        select s.student_id as student_id,
            s.full_name as student_full_name,
            s.date_of_birth as student_date_of_birth,
            s.address as student_address,
            s.parent_phone_number as student_parent_phone_number,
            c.class_name as class_name,
            c.academic_year as class_academic_year,
            t.teacher_id as teacher_id,
            t.full_name as teacher_full_name,
            t.phone_number as teacher_phone_number,
            m.semester,
            m.oral_score as mark_oral_score,
            m._15_minutes_score as mark__15_minutes_score,
            m._1_period_score as mark__1_period_score,
            m.semester_score as mark_semester_score,
            sj.*
        from students as s join marks as m
                on s.student_id = m.student_id
            join subjects as sj
                on m.subject_id = sj.subject_id
            join classes as c
                on s.class_id = c.class_id
            join homeroom_teachers as ht
                on c.class_id = ht.class_id
            join teachers as t
                on ht.teacher_id = t.teacher_id
        where s.student_id = _student_id;
    end if;
end $$



call add_account('admin', 'admin', 'admin');
call add_account('teacher1', 'teacher', 'teacher');
call add_account('teacher2', 'teacher', 'teacher');
call add_account('teacher3', 'teacher', 'teacher');

call add_class(-1, '10A1', '2023-2024');
call add_class(-1, '10A2', '2023-2024');
call add_class(-1, '10A3', '2023-2024');
call add_class(-1, '10A4', '2023-2024');
call add_class(-1, '10A5', '2023-2024');
call add_class(-1, '10B1', '2023-2024');
call add_class(-1, '10B2', '2023-2024');
call add_class(-1, '10B3', '2023-2024');
call add_class(-1, '10B4', '2023-2024');
call add_class(-1, '11A1', '2023-2024');

call add_teacher(-1, 'Nguyễn Thị Hương', '1980-05-15', '123 ABC Street, XYZ City', '0123456789');
call add_teacher(-1, 'Trần Đức Minh', '1985-09-20', '456 XYZ Street, ABC City', '0987654321');
call add_teacher(-1, 'Phạm Anh Tuấn', '1975-12-10', '789 DEF Street, GHI City', '0369852147');
call add_teacher(-1, 'Lê Thị Ngọc Anh', '1990-03-25', '321 GHI Street, DEF City', '0542136987');
call add_teacher(-1, 'Hoàng Văn Nam', '1988-07-08', '654 JKL Street, MNO City', '0789654321');
call add_teacher(-1, 'Mai Thanh Hà', '1972-10-30', '987 MNO Street, JKL City', '0912345678');
call add_teacher(-1, 'Đặng Tuấn Anh', '1983-04-12', '147 PQR Street, STU City', '0654321897');
call add_teacher(-1, 'Vũ Thị Lan', '1987-06-18', '258 STU Street, PQR City', '0321654987');
call add_teacher(-1, 'Trương Minh Trí', '1979-11-05', '369 VWX Street, YZ City', '0965741238');
call add_teacher(-1, 'Lý Thị Mai', '1981-08-28', '741 YZ Street, VWX City', '0398574126');
call add_teacher(-1, 'Nguyễn Văn Tâm', '1984-02-14', '852 QWE Street, RTY City', '0789642135');
call add_teacher(-1, 'Trần Thị Thu', '1976-09-03', '963 RTY Street, QWE City', '0932145678');
call add_teacher(-1, 'Phạm Quang Hải', '1989-01-20', '147 ASD Street, FGH City', '0678954123');
call add_teacher(-1, 'Lê Đình Thắng', '1982-11-09', '258 FGH Street, ASD City', '0354789652');
call add_teacher(-1, 'Hoàng Thị Hà', '1977-07-06', '369 ZXC Street, VBN City', '0923456789');
call add_teacher(-1, 'Mai Văn Dương', '1986-03-30', '147 VBN Street, ZXC City', '0897456321');
call add_teacher(-1, 'Đặng Thị Thu', '1974-05-18', '258 XCV Street, BNM City', '0745123698');
call add_teacher(-1, 'Vũ Minh Tuấn', '1983-08-12', '369 BNM Street, XCV City', '0912345678');
call add_teacher(-1, 'Trương Thanh Hương', '1978-12-25', '741 MNB Street, VCX City', '0654789123');
call add_teacher(-1, 'Lý Thị Lan', '1985-10-08', '852 VCX Street, MNB City', '0932147856');
call add_teacher(-1, 'Nguyễn Văn Hoàng', '1973-06-15', '963 CXV Street, BNM City', '0789654123');
call add_teacher(-1, 'Trần Thị Ngọc', '1980-04-02', '147 BNM Street, CXV City', '0369854712');
call add_teacher(-1, 'Phạm Văn Bình', '1987-09-10', '258 ASD Street, FGH City', '0987456321');
call add_teacher(-1, 'Lê Thị Thúy', '1971-03-28', '369 FGH Street, ASD City', '0654789321');
call add_teacher(-1, 'Hoàng Văn Sơn', '1984-11-15', '741 QWE Street, RTY City', '0923784561');
call add_teacher(-1, 'Mai Thị Huệ', '1979-07-01', '852 RTY Street, QWE City', '0789654123');
call add_teacher(-1, 'Đặng Minh Hiếu', '1982-02-18', '963 XCV Street, BNM City', '0654789321');
call add_teacher(-1, 'Vũ Thị Hương', '1975-08-05', '147 BNM Street, XCV City', '0932147856');
call add_teacher(-1, 'Trương Văn Đức', '1988-04-22', '258 VCX Street, MNB City', '0923784651');
call add_teacher(-1, 'Lý Thanh Tùng', '1972-12-10', '369 MNB Street, VCX City', '0987456321');
call add_teacher(-1, 'Nguyễn Thị Kim', '1981-06-25', '741 CXV Street, BNM City', '0789654123');
call add_teacher(-1, 'Trần Văn Dũng', '1976-09-13', '852 BNM Street, CXV City', '0654789321');
call add_teacher(-1, 'Phạm Thị Hương', '1983-03-20', '963 ASD Street, FGH City', '0932147856');
call add_teacher(-1, 'Lê Văn Hòa', '1974-10-18', '147 FGH Street, ASD City', '0923784651');
call add_teacher(-1, 'Hoàng Thị Mai', '1985-07-05', '258 QWE Street, RTY City', '0987456321');
call add_teacher(-1, 'Mai Văn Tú', '1977-01-22', '369 RTY Street, QWE City', '0789654123');
call add_student(-1, 'Nguyễn Thị Lan', '2007-05-15', '123 ABC Street, XYZ City', '0123456789', 1);
call add_student(-1, 'Trần Văn Đức', '2006-09-20', '456 XYZ Street, ABC City', '0987654321', 2);
call add_student(-1, 'Phạm Thị Hương', '2008-12-10', '789 DEF Street, GHI City', '0369852147', 3);
call add_student(-1, 'Lê Văn Hòa', '2007-03-25', '321 GHI Street, DEF City', '0542136987', 4);
call add_student(-1, 'Hoàng Thị Mai', '2006-07-08', '654 JKL Street, MNO City', '0789654321', 5);
call add_student(-1, 'Mai Văn Tú', '2008-10-30', '987 MNO Street, JKL City', '0912345678', 6);
call add_student(-1, 'Đặng Minh Hiếu', '2007-04-12', '147 PQR Street, STU City', '0654321897', 7);
call add_student(-1, 'Vũ Thị Hương', '2006-06-18', '258 STU Street, PQR City', '0321654987', 8);
call add_student(-1, 'Trương Văn Đức', '2008-11-05', '369 VWX Street, YZ City', '0965741238', 9);
call add_student(-1, 'Lý Thanh Tùng', '2007-08-28', '741 YZ Street, VWX City', '0398574126', 10);
call add_student(-1, 'Nguyễn Thị Anh', '2007-09-15', '123 ABC Street, XYZ City', '0123456789', 1);
call add_student(-1, 'Trần Văn An', '2006-08-20', '456 XYZ Street, ABC City', '0987654321', 2);
call add_student(-1, 'Phạm Thị Bình', '2008-11-10', '789 DEF Street, GHI City', '0369852147', 3);
call add_student(-1, 'Lê Văn Cường', '2007-02-25', '321 GHI Street, DEF City', '0542136987', 4);
call add_student(-1, 'Hoàng Thị Dung', '2006-04-08', '654 JKL Street, MNO City', '0789654321', 5);
call add_student(-1, 'Mai Thị Hạnh', '2008-07-30', '987 MNO Street, JKL City', '0912345678', 6);
call add_student(-1, 'Đặng Văn Đông', '2007-10-12', '147 PQR Street, STU City', '0654321897', 7);
call add_student(-1, 'Vũ Thị Linh', '2006-06-18', '258 STU Street, PQR City', '0321654987', 8);
call add_student(-1, 'Trương Văn Tài', '2008-05-05', '369 VWX Street, YZ City', '0965741238', 9);
call add_student(-1, 'Nguyễn Văn Dương', '2007-09-15', '123 ABC Street, XYZ City', '0123456789', 1);
call add_student(-1, 'Trần Thị Hồng', '2006-08-20', '456 XYZ Street, ABC City', '0987654321', 2);
call add_student(-1, 'Phạm Văn Nam', '2008-11-10', '789 DEF Street, GHI City', '0369852147', 3);
call add_student(-1, 'Lê Thị Thảo', '2007-02-25', '321 GHI Street, DEF City', '0542136987', 4);
call add_student(-1, 'Hoàng Văn Anh', '2006-04-08', '654 JKL Street, MNO City', '0789654321', 5);
call add_student(-1, 'Mai Văn Hùng', '2008-07-30', '987 MNO Street, JKL City', '0912345678', 6);
call add_student(-1, 'Đặng Thị Thu', '2007-10-12', '147 PQR Street, STU City', '0654321897', 7);
call add_student(-1, 'Vũ Thị Huệ', '2006-06-18', '258 STU Street, PQR City', '0321654987', 8);
call add_student(-1, 'Trương Văn Hòa', '2008-05-05', '369 VWX Street, YZ City', '0965741238', 9);
call add_student(-1, 'Lý Thị Kim', '2007-03-28', '741 YZ Street, VWX City', '0398574126', 10);
call add_student(-1, 'Trần Văn An', '2006-08-20', '456 XYZ Street, ABC City', '0987654321', 2);
call add_student(-1, 'Phạm Thị Bình', '2008-11-10', '789 DEF Street, GHI City', '0369852147', 3);
call add_student(-1, 'Lê Văn Cường', '2007-02-25', '321 GHI Street, DEF City', '0542136987', 4);
call add_student(-1, 'Hoàng Thị Dung', '2006-04-08', '654 JKL Street, MNO City', '0789654321', 5);
call add_student(-1, 'Mai Thị Hạnh', '2008-07-30', '987 MNO Street, JKL City', '0912345678', 6);
call add_student(-1, 'Đặng Văn Đông', '2007-10-12', '147 PQR Street, STU City', '0654321897', 7);
call add_student(-1, 'Vũ Thị Linh', '2006-06-18', '258 STU Street, PQR City', '0321654987', 8);
call add_student(-1, 'Trương Văn Tài', '2008-05-05', '369 VWX Street, YZ City', '0965741238', 9);
call add_student(-1, 'Lý Thị Quỳnh', '2007-03-28', '741 YZ Street, VWX City', '0398574126', 10);
call add_student(-1, 'Trần Văn An', '2006-08-20', '456 XYZ Street, ABC City', '0987654321', 2);
call add_student(-1, 'Phạm Thị Bình', '2008-11-10', '789 DEF Street, GHI City', '0369852147', 3);
call add_student(-1, 'Lê Văn Cường', '2007-02-25', '321 GHI Street, DEF City', '0542136987', 4);
call add_student(-1, 'Hoàng Thị Dung', '2006-04-08', '654 JKL Street, MNO City', '0789654321', 5);
call add_student(-1, 'Mai Thị Hạnh', '2008-07-30', '987 MNO Street, JKL City', '0912345678', 6);
call add_student(-1, 'Đặng Văn Đông', '2007-10-12', '147 PQR Street, STU City', '0654321897', 7);
call add_student(-1, 'Vũ Thị Linh', '2006-06-18', '258 STU Street, PQR City', '0321654987', 8);
call add_student(-1, 'Trương Văn Tài', '2008-05-05', '369 VWX Street, YZ City', '0965741238', 9);
call add_student(-1, 'Lý Thị Quỳnh', '2007-03-28', '741 YZ Street, VWX City', '0398574126', 10);
call add_student(-1, 'Trần Văn Anh', '2006-09-20', '456 XYZ Street, ABC City', '0987654321', 2);
call add_student(-1, 'Phạm Thị Bảo', '2008-12-10', '789 DEF Street, GHI City', '0369852147', 3);
call add_student(-1, 'Lê Văn Cường', '2007-03-25', '321 GHI Street, DEF City', '0542136987', 4);
call add_student(-1, 'Hoàng Thị Dung', '2006-07-08', '654 JKL Street, MNO City', '0789654321', 5);
call add_student(-1, 'Mai Thị Hạnh', '2008-10-30', '987 MNO Street, JKL City', '0912345678', 6);
call add_student(-1, 'Đặng Minh Hiếu', '2007-04-12', '147 PQR Street, STU City', '0654321897', 7);
call add_student(-1, 'Vũ Thị Lan', '2006-06-18', '258 STU Street, PQR City', '0321654987', 8);
call add_student(-1, 'Trương Văn Tài', '2008-05-05', '369 VWX Street, YZ City', '0965741238', 9);
call add_student(-1, 'Lý Thị Mai', '2007-08-28', '741 YZ Street, VWX City', '0398574126', 10);
call add_student(-1, 'Trần Văn Anh', '2006-09-20', '456 XYZ Street, ABC City', '0987654321', 2);
call add_student(-1, 'Phạm Thị Bảo', '2008-12-10', '789 DEF Street, GHI City', '0369852147', 3);
call add_student(-1, 'Lê Văn Cường', '2007-03-25', '321 GHI Street, DEF City', '0542136987', 4);
call add_student(-1, 'Hoàng Thị Dung', '2006-07-08', '654 JKL Street, MNO City', '0789654321', 5);
call add_student(-1, 'Mai Thị Hạnh', '2008-10-30', '987 MNO Street, JKL City', '0912345678', 6);
call add_student(-1, 'Đặng Minh Hiếu', '2007-04-12', '147 PQR Street, STU City', '0654321897', 7);
call add_student(-1, 'Vũ Thị Lan', '2006-06-18', '258 STU Street, PQR City', '0321654987', 8);
call add_student(-1, 'Trương Văn Tài', '2008-05-05', '369 VWX Street, YZ City', '0965741238', 9);
call add_student(-1, 'Lý Thị Mai', '2007-08-28', '741 YZ Street, VWX City', '0398574126', 10);

CALL add_room(-1, '101', 30);
CALL add_room(-1, '102', 25);
CALL add_room(-1, '103', 35);
CALL add_room(-1, '104', 28);
CALL add_room(-1, '105', 32);
CALL add_room(-1, '106', 30);
CALL add_room(-1, '107', 25);
CALL add_room(-1, '108', 35);
CALL add_room(-1, '109', 28);
CALL add_room(-1, '110', 32);
CALL add_room(-1, '201', 30);
CALL add_room(-1, '202', 25);
CALL add_room(-1, '203', 35);
CALL add_room(-1, '204', 28);
CALL add_room(-1, '205', 32);
CALL add_room(-1, '206', 30);
CALL add_room(-1, '207', 25);
CALL add_room(-1, '208', 35);
CALL add_room(-1, '209', 28);
CALL add_room(-1, '210', 32);

CALL add_subject(-1, 'Toán', 10);
CALL add_subject(-1, 'Vật lí', 10);
CALL add_subject(-1, 'Hóa học', 10);
CALL add_subject(-1, 'Sinh học', 10);
CALL add_subject(-1, 'Ngữ văn', 10);
CALL add_subject(-1, 'Lịch sử', 10);
CALL add_subject(-1, 'Địa lý', 10);
CALL add_subject(-1, 'Ngoại ngữ', 10);
CALL add_subject(-1, 'Tin học', 10);
CALL add_subject(-1, 'Giáo dục công dân', 10);
CALL add_subject(-1, 'Toán', 11);
CALL add_subject(-1, 'Vật lí', 11);
CALL add_subject(-1, 'Hóa học', 11);
CALL add_subject(-1, 'Sinh học', 11);
CALL add_subject(-1, 'Ngữ văn', 11);
CALL add_subject(-1, 'Lịch sử', 11);
CALL add_subject(-1, 'Địa lý', 11);
CALL add_subject(-1, 'Ngoại ngữ', 11);
CALL add_subject(-1, 'Tin học', 11);
CALL add_subject(-1, 'Giáo dục công dân', 11);
CALL add_subject(-1, 'Toán', 12);
CALL add_subject(-1, 'Vật lí', 12);
CALL add_subject(-1, 'Hóa học', 12);
CALL add_subject(-1, 'Sinh học', 12);
CALL add_subject(-1, 'Ngữ văn', 12);
CALL add_subject(-1, 'Lịch sử', 12);
CALL add_subject(-1, 'Địa lý', 12);
CALL add_subject(-1, 'Ngoại ngữ', 12);
CALL add_subject(-1, 'Tin học', 12);
CALL add_subject(-1, 'Giáo dục công dân', 12);

CALL add_room_class(1, 1, 1);
CALL add_room_class(2, 2, 1);
CALL add_room_class(3, 3, 1);
CALL add_room_class(4, 4, 1);
CALL add_room_class(5, 5, 1);
CALL add_room_class(6, 6, 1);
CALL add_room_class(7, 7, 1);
CALL add_room_class(8, 8, 1);
CALL add_room_class(9, 9, 1);
CALL add_room_class(10, 10, 1);

call add_homeroom_teacher(1, 1);
call add_homeroom_teacher(2, 2);
call add_homeroom_teacher(3, 3);
call add_homeroom_teacher(4, 4);
call add_homeroom_teacher(5, 5);
call add_homeroom_teacher(6, 6);
call add_homeroom_teacher(7, 7);
call add_homeroom_teacher(8, 8);
call add_homeroom_teacher(9, 9);
call add_homeroom_teacher(10, 10);


CALL add_mark(1, 1, 1, 8.5, 7.5, 8.0, 8.0);
CALL add_mark(1, 2, 1, 7.5, 8.5, 8.0, 8.0);
CALL add_mark(1, 3, 1, 8.0, 8.0, 8.5, 8.0);
CALL add_mark(1, 4, 1, 8.5, 8.0, 8.0, 8.0);
CALL add_mark(1, 5, 1, 8.0, 8.5, 8.0, 8.0);
CALL add_mark(1, 6, 1, 8.0, 8.0, 8.5, 8.0);
CALL add_mark(1, 7, 1, 8.5, 8.0, 8.0, 8.0);
CALL add_mark(1, 8, 1, 8.0, 8.5, 8.0, 8.0);
CALL add_mark(1, 9, 1, 8.0, 8.0, 8.5, 8.0);
CALL add_mark(1, 10, 1, 8.5, 8.0, 8.0, 8.0);
CALL add_mark(2, 1, 1, 8.0, 8.5, 8.0, 8.0);
CALL add_mark(2, 2, 1, 8.5, 8.0, 8.0, 8.0);
CALL add_mark(2, 3, 1, 8.0, 8.5, 8.0, 8.0);
CALL add_mark(2, 4, 1, 8.0, 8.0, 8.5, 8.0);
CALL add_mark(2, 5, 1, 4.5, 8.0, 8.0, 8.0);
CALL add_mark(2, 6, 1, 8.0, 8.5, 8.0, 8.0);
CALL add_mark(2, 7, 1, 8.0, 8.0, 8.5, 8.0);
CALL add_mark(2, 8, 1, 8.5, 8.0, 8.0, 8.0);
CALL add_mark(2, 9, 1, 8.0, 8.5, 8.0, 8.0);
CALL add_mark(2, 10, 1, 8.0, 8.0, 8.5, 8.0);
CALL add_mark(3, 1, 1, 8.5, 8.0, 8.0, 8.0);
CALL add_mark(3, 2, 1, 8.0, 8.5, 8.0, 8.0);
CALL add_mark(3, 3, 1, 8.0, 8.0, 8.5, 8.0);
CALL add_mark(3, 4, 1, 8.5, 8.0, 8.0, 8.0);
CALL add_mark(3, 5, 1, 8.0, 8.5, 8.0, 8.0);
CALL add_mark(3, 6, 1, 8.0, 8.0, 8.5, 8.0);
CALL add_mark(3, 7, 1, 8.5, 8.0, 8.0, 8.0);
CALL add_mark(3, 8, 1, 8.0, 8.5, 8.0, 8.0);
CALL add_mark(3, 9, 1, 8.0, 8.0, 8.5, 8.0);
CALL add_mark(3, 10, 1, 8.5, 8.0, 8.0, 8.0);
CALL add_mark(4, 1, 1, 8.0, 8.5, 8.0, 8.0);
CALL add_mark(4, 2, 1, 8.5, 8.0, 8.0, 8.0);
CALL add_mark(4, 3, 1, 8.0, 8.5, 8.0, 8.0);
CALL add_mark(4, 4, 1, 8.0, 8.0, 8.5, 8.0);
CALL add_mark(4, 5, 1, 8.5, 8.0, 8.0, 8.0);
CALL add_mark(4, 6, 1, 8.0, 8.5, 8.0, 8.0);
CALL add_mark(4, 7, 1, 8.0, 8.0, 8.5, 8.0);
CALL add_mark(4, 8, 1, 8.5, 8.0, 8.0, 8.0);
CALL add_mark(4, 9, 1, 8.0, 8.5, 8.0, 8.0);
CALL add_mark(4, 10, 1, 8.0, 8.0, 8.5, 8.0);