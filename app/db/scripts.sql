create database if not exists high_school_management;

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
  oral_score decimal(4, 2) not null,
  15_minutes_score decimal(4, 2) not null,
  1_period_score decimal(4, 2) not null,
  semester_score decimal(4, 2) not null,
  primary key (student_id, subject_id),
  foreign key (student_id) references students(student_id),
  foreign key (subject_id) references subjects(subject_id)
);
create table if not exists teaching (
  teacher_id int not null,
  subject_id int not null,
  semester tinyint not null,
  academic_year char(9) not null,
  primary key (teacher_id, subject_id, semester, academic_year),
  foreign key (teacher_id) references teachers(teacher_id),
  foreign key (subject_id) references subjects(subject_id),
  foreign key (academic_year) references academic_years(academic_year)
);
create table if not exists users (
  email varchar(50) primary key,
  password varchar(255) not null,
  role varchar(10) not null
);
