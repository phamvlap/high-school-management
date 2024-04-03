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