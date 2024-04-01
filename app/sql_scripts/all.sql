drop database if exists high_school_management;
create database high_school_management;

use high_school_management;

create table if not exists academic_years (
    academic_year char(9) primary key
);
create table if not exists classes (
    class_id int primary key auto_increment,
    class_name varchar(10) not null,
    academic_year char(9) not null references academic_year(academic_year)
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
    class_id int not null,
    foreign key (class_id) references classes(class_id)
);
create table if not exists homeroom_teachers (
    teacher_id int not null,
    class_id int not null,
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
    semester tinyint not null,
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
    oral_score decimal(4, 2) not null,
    _15_minutes_score decimal(4, 2) not null,
    _1_period_score decimal(4, 2) not null,
    semester_score decimal(4, 2) not null,
    primary key (student_id, subject_id, semester),
    foreign key (student_id) references students(student_id),
    foreign key (subject_id) references subjects(subject_id)
);
create table if not exists teaching (
    teacher_id int not null,
    subject_id int not null,
    academic_year char(9) not null,
    primary key (teacher_id, subject_id, academic_year),
    foreign key (teacher_id) references teachers(teacher_id),
    foreign key (subject_id) references subjects(subject_id),
    foreign key (academic_year) references academic_years(academic_year)
);
create table if not exists accounts (
    username varchar(50) primary key,
    password varchar(255) not null,
    type varchar(10) not null
);
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
-- [example]: call get_all_accounts();use high_school_management;
delimiter $$

-- [procedure]: add_class(_class_id, _class_name, _academic_year)
-- [author]: phamvlap
drop procedure if exists add_class $$
create procedure add_class(
	in _class_id int,
	in _class_name varchar(10),
	in _academic_year char(9)
)
begin
	if (_class_id = -1)
		then
			if not exists (
				select academic_year
				from academic_years
				where academic_year = _academic_year
			)
				then
					insert into academic_years(academic_year)
						values(_academic_year);
			end if;
			insert into classes(class_name, academic_year)
				values(_class_name, _academic_year);
		else
			set @old_year = (
				select academic_year
				from classes
				where class_id = _class_id
			);
			if not exists (
				select academic_year
				from academic_years
				where academic_year = _academic_year
			)
				then
					insert into academic_years(academic_year)
						values(_academic_year);
					
					update classes
					set class_name = _class_name, academic_year = _academic_year
					where class_id = _class_id;
				else
					update classes
					set class_name = _class_name, academic_year = _academic_year
					where class_id = _class_id;
					
					if not exists (
						select *
						from classes
						where academic_year = @old_year
					)
						then
							delete from academic_years
							where academic_year = @old_year;
					end if;
			end if;
	end if;
end $$

-- [example]:
-- call add_class(-1, '10A5', '2023-2024');
-- call add_class(-1, '11A5', '2022-2023');
-- call add_class(-1, '12A5', '2023-2024');
-- call add_class(2, '10A6', '2023-2024');

-- [procedure]: delete_class(_class_id)
-- [author]: phamvlap
delimiter $$
drop procedure if exists delete_class $$
create procedure delete_class(
	in _class_id int
)
begin
	delete from classes 
    where class_id = _class_id;
end $$

-- [example]:
call delete_class(2);

-- [function]: get_all(_class_name, _grade, _academic_year)
-- [author]: phamvlap
delimiter $$
drop procedure if exists get_all_classes $$

create procedure get_all_classes(
	in _class_name varchar(10),
	in _grade varchar(10),
	in _academic_year char(9),
    in _is_order_by_class_id int
)
begin
	select c.class_id, c.class_name, c.academic_year, 
			t.teacher_id, t.full_name, t.date_of_birth, t.address, t.phone_number,
            rc.room_id, rc.semester,
            r.room_number, r.maximum_capacity
	from classes as c
		left join homeroom_teachers as ht
			on c.class_id = ht.class_id 
		left join teachers as t
			on ht.teacher_id = t.teacher_id
		left join room_class as rc
			on c.class_id = rc.class_id
		left join rooms as r
			on rc.room_id = r.room_id
	where (_class_name is null or c.class_name like concat('%', _class_name, '%'))
		and (_grade is null or c.class_name like concat('%', _grade, '%'))
		and (_academic_year is null or c.academic_year = _academic_year)
	order by
		case when _is_order_by_class_id = 1 then c.class_id end asc,
		case when _is_order_by_class_id = 0 then c.class_name end asc;
end $$

-- [example]:
-- call get_all_classes(null, null, null);

-- [procedure]: get_by_id(_class_id)
-- [author]: phamvlap
drop procedure if exists get_by_id $$
create procedure get_class_by_id(
	in _class_id int
)
begin
	select *
	from room_class as rc
		join homeroom_teachers as ht
			on rc.class_id = ht.class_id 
		join teachers as t
			on ht.teacher_id = t.teacher_id
		join classes as c
			on c.class_id = rc.class_id
		join rooms as r
			on rc.room_id = r.room_id
	where c.class_id = _class_id;
end $$



use high_school_management;
delimiter $$

-- [procedure]: add_homeroom_teacher(_teacher_id, _class_id)
-- [author]: phamvlap
drop procedure if exists add_homeroom_teacher $$
create procedure add_homeroom_teacher(
	in _teacher_id int,
	in _class_id int
)
begin
	insert into homeroom_teachers(teacher_id, class_id)
		values(_teacher_id, _class_id);
end $$

-- [procedure]: delete_homeroom_teacher(_teacher_id, _class_id)
-- [author]: phamvlap
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

-- [procedure]: update_homeroom_teacher(_teacher_id, _class_id)
-- [author]: phamvlap
-- [fix]: camtu 30/3/2024
drop procedure if exists update_homeroom_teacher $$
create procedure update_homeroom_teacher(
	in _teacher_id int,
	in _class_id int,
	in _new_teacher_id int
)
begin
	call delete_homeroom_teacher(_teacher_id, _class_id);
	call add_homeroom_teacher(_new_teacher_id, _class_id);
end $$
delimiter $$

-- [procedure]:	update_mark(_student_id, _subject_id, _semester, _oral_score, __15_minutes_score, __1_period_score, _semester_score)
-- [author]: cuong
create procedure add_mark(	
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
        select * from marks
        where student_id = _student_id and subject_id = _subject_id and semester = _semester
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
-- [example]: call add_mark(1, 2, 1, 3, 4, 5, 6);

-- [procedure]:	delete_mark(_student_id, _subject_id, _semester)
-- [author]: cuong
drop procedure if exists detete_mark$$
create procedure delete_mark(
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
-- [example]: call delete_mark(1, 2, 1);

-- [procedure]:	get_mark_by_student_id(_student_id, _subject_id, _semester)
-- [author]: cuong
drop procedure if exists get_all_mark$$
create procedure get_all_mark(
    in _student_id int,
    in _subject_id int,
    in _semester tinyint
)
begin
    select * 
    from marks as m
        join students as s on m.student_id = s.student_id
        join subjects as sb on m.subject_id = sb.subject_id
    where 
        (_student_id is null or s.student_id = _student_id)
        and (_subject_id is null or sb.subject_id = _subject_id)
        and (_semester is null or m.semester = _semester);
end $$
-- [example]: call get_all_mark(3, null, null);delimiter $$

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
-- [fix]: camtu 30/3/2024
drop procedure if exists update_room_class $$
create procedure update_room_class(
	in _room_id int, 
	in _class_id int, 
	in _semester tinyint, 
	in _new_room_id int,
    in _new_semester tinyint
)
begin 
	call add_room_class(_new_room_id,_class_id, _new_semester);
	call delete_room_class(_room_id, _class_id, _semester);
end $$
-- [example]:
-- call update_room_class(1, 1, 1, 2, 2, 2);
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
-- [example]: call get_all_rooms(null, null, null, 0, 1);use high_school_management;
delimiter $$

-- [procedure]: add_student(full_name, date_of_birth, address, parent_phone_number, class_id)
-- [author]: camtu
create procedure add_student	(
	in _student_id int,
	in _full_name varchar(255),
	in _date_of_birth date,
	in _address varchar(255),
	in _parent_phone_number char(10),
	in _class_id int,
	in _academic_year char(9)
)
begin
	if exists (
		select 1
		from academic_years as years
		where years.academic_year = _academic_year
	) 
    then
		if exists (
			select 1
			from classes
			where classes.class_id = _class_id
		) 
		then
			if (_student_id = -1)
			then
				insert into students (full_name, date_of_birth, address, parent_phone_number, class_id) 
					values (_full_name, _date_of_birth, _address, _parent_phone_number, _class_id);
			else
				update students 
                set full_name = _full_name, 
					date_of_birth = date_of_birth, 
					address = _address, 
					parent_phone_number = _parent_phone_number, 
					class_id = _class_id
				where students.student_id = _student_id;
			end if;
		end if;
	end if;
end $$

-- [excample]: call add_student(1, 'Nguyen Van A', '2000-01-01', 'Ha Noi', '0123456789', 1, '2020-2021');

-- [procedure]: delete_student(student_id)
-- [author]: camtu
drop procedure if exists delete_student $$
create procedure delete_student	(
	in _student_id int
)
begin
    delete from students 
	where student_id = _student_id;
end $$
-- [excample]: call delete_student(1);

-- [procedure]: update_students_class_id(class_id)
-- [author]: camtu
delimiter $$
drop procedure if exists  delete_students_from_class $$
create procedure  delete_students_from_class (
	in _class_id int
)
begin
   update students
   set class_id = _new_class_id
   where class_id = _class_id;
end $$

-- [procedure]: delete_student(student_id)
-- [author]: camtu
drop procedure if exists delete_student $$
create procedure delete_student	(
	in _student_id int
)
begin
    delete from students 
	where student_id = _student_id;
end $$
-- [excample]: call delete_student(1);

-- [procedure]: get_student_by_id(student_id)
-- [author]: camtu
drop procedure if exists get_student_by_id $$
create procedure get_student_by_id (
	in _student_id int
)
begin
	select *
	from students 
	where student_id = _student_id;
end $$

-- [excample]: call get_student_by_id(1);

-- [procedure]: get_all_students(_address, _class_id, _academic_year)
-- [author]: camtu
delimiter $$
drop procedure if exists get_all_students $$
create procedure get_all_students   ( 
	in  _address varchar(255),
	in _class_id int,
	in _academic_year char(9),
	in _is_sort_by_name_desc tinyint
)
begin
    select * 
	from students 
		join classes on students.class_id = classes.class_id
	where (_address is null or address like concat('%', concat( _address, '%'))) 
		  and (_class_id is null or students.class_id = _class_id)
		  and (_academic_year is null or academic_year like concat('%', concat( _academic_year, '%')))
	order by 
		case when _is_sort_by_name_desc = 1 then full_name end desc,
		case when _is_sort_by_name_desc = 0 then full_name end asc;
end $$

-- [excample]: call get_all_students('Ha Noi','2',null);

-- ----------------------------------------------------------------------------------------------------------------------------

-- [procedure]: delete_all_students()
-- [author]: camtu
drop procedure if exists delete_all_students $$
create procedure delete_all_students ()
begin
    delete from students;
end $$

-- [excample]: call delete_all_students();
use high_school_management;
delimiter $$

-- [procedure]: add_subject(subject_id, subject_name, grade)
-- author: camtu
drop procedure if exists add_subject $$
create procedure add_subject ( 
    in _subject_id int ,
    in _subject_name varchar(255),
    in _grade tinyint
)
begin 
    if(_subject_id = -1)
    then
        insert into subjects (subject_name, grade) 
            values ( _subject_name, _grade);
    else
        update subjects 
        set subject_name = _subject_name, grade = _grade 
        where subject_id = _subject_id;
    end if;
end $$

-- [example]: call add_subject(1, 'English', 1);

-- [procedure]: delete_subject(subject_id)
-- [author]: camtu

drop procedure if exists delete_subject $$
create procedure delete_subject (
    in _subject_id int
)
begin
    delete from subjects
    where subject_id = _subject_id;
end $$

-- [example]: call delete_subject(1);
-- [procedure]: update_subject(subject_id, subject_name, grade)
-- author: camtu

-- [example]: call update_subject(1, 'English', 1);

-- [procedure]: get_subject_by_id(subject_id)
-- author: camtu
drop procedure if exists get_subject_by_id $$
create procedure get_subject_by_id (
    in _subject_id int
)
begin
    select * 
    from subjects 
    where subject_id = _subject_id;
end $$

-- [example]: call get_subject_by_id(1);

-- [procedure]: get_all_subject(subject_name, grade)
-- author: camtu
drop procedure if exists get_all_subject $$
create procedure get_all_subject (
    in _subject_name varchar(255), 
    in _grade tinyint, 
    in _is_sort_by_grade_desc tinyint
)
begin
    select * 
    from subjects 
    where (_subject_name is null or subject_name like concat('%', concat(_subject_name, '%')))
          and (_grade is null or grade = _grade)
    order by 
        case when _is_sort_by_grade_desc = 1 then grade end desc,
        case when _is_sort_by_grade_desc = 0 then grade end asc;
end $$

-- [example]: call get_all_subject('English', 1, 1);
delimiter $$

-- [procedure]:	add_teacher(_teacher_id, _full_name, _date_of_birth, _address, _phone_number)
-- [author]: cuong
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
	in _date_of_birth_start date,
    in _date_of_birth_end date, 
	in _address varchar(255),
	in is_order_by_name tinyint
)
begin
    select * 
    from teachers 
    where 
        (_full_name is null or full_name like concat('%',_full_name,'%')) 
        and (_date_of_birth_start is null or date_of_birth >= _date_of_birth_start)
        and (_date_of_birth_end is null or date_of_birth <= _date_of_birth_end)
        and (_address is null or address like concat('%',_address,'%'))
        order by
            case when is_order_by_name = 1 then full_name end asc,
            case when is_order_by_name = 0 then full_name end desc;
end $$
-- [example]: call get_all_teachers(null, null, null, null, 1);


use high_school_management;
delimiter $$

-- [procedure]: add_teaching(teacher_id, subject_id, academic_year)
-- [author]: phamvlap
drop procedure if exists add_teaching $$
create procedure add_teaching(
	in _teacher_id int, 
	in _subject_id int,
	in _academic_year char(9)
)
begin
	insert into teaching(teacher_id, subject_id, academic_year)
		values(_teacher_id, _subject_id, _academic_year);
end $$

-- [procedure]: delete_teaching(teacher_id, subject_id, academic_year)
-- [author]: phamvlap
drop procedure if exists delete_teaching $$
create procedure delete_teaching(
	in _teacher_id int,
	in _subject_id int,
	in _academic_year char(9)
)
begin
	delete from teaching
	where teacher_id = _teacher_id
		and subject_id = _subject_id
		and academic_year = _academic_year;
end $$

-- [procedure]: update_teaching(teacher_id, subject_id, academic_year)
-- [author]: phamvlap
drop procedure if exists update_teaching $$
create procedure update_teaching(
	in _teacher_id int,
	in _subject_id int, 
	in _academic_year char(9)
)
begin
	call delete_teaching(_teacher_id, _subject_id, _academic_year);
	call add_teaching(_teacher_id, _subject_id, _academic_year);
end $$

-- [function]: get_class_id(_class_name, _grade, _academic_year)
-- [author]: camtu
delimiter $$
drop function if exists get_class_id $$
create function get_classId(
	_class_name varchar(10),
	_academic_year char(9)
)
returns int
begin
	declare selected_class_id int;
    
	select class_id into selected_class_id
	from classes 
    where class_name = _class_name
		and academic_year = _academic_year
	limit 1,1;
	return selected_class_id;
		
end $$
