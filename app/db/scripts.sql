create database 'high-school-management';

create table class (
  class_id int primary key auto_increment,
  class_name varchar(10) not null,
  academic_year char(9) not null references academic_year(academic_year)
);

create table teacher (
  teacher_id int primary key auto_increment,
  full_name varchar(255) not null,
  date_of_birth date not null,
  address varchar(255) not null,
  phone_number char(10) not null
);

create table student (
  student_id int primary key auto_increment,
  full_name varchar(255) not null,
  date_of_birth date not null,
  address varchar(255) not null,
  parent_phone_number char(10) not null,
  class_id int not null,
  foreign key (class_id) references class(class_id)
);

create table homeroom_teacher (
  teacher_id int not null,
  class_id int not null,
  primary key (teacher_id, class_id),
  foreign key (teacher_id) references teacher(teacher_id),
  foreign key (class_id) references class(class_id)
);

create table room (
  room_id int primary key auto_increment,
  room_number varchar(50) not null,
  maximum_capacity int not null
);

create table room_class (
  room_id int not null,
  class_id int not null,
  semester tinyint not null,
  primary key (room_id, class_id, semester),
  foreign key (room_id) references room(room_id),
  foreign key (class_id) references class(class_id)
);

create table subject (
  subject_id int primary key auto_increment,
  subject_name varchar(255) not null,
  grade tinyint not null
);

create table mark (
  student_id int not null,
  subject_id int not null,
  oral_score decimal(4, 2) not null,
  15_minutes_score decimal(4, 2) not null,
  1_period_score decimal(4, 2) not null,
  semester_score decimal(4, 2) not null,
  primary key (student_id, subject_id),
  foreign key (student_id) references student(student_id),
  foreign key (subject_id) references subject(subject_id)
);

create table academic_year (
  academic_year char(9) primary key
);

create table teaching (
  teacher_id int not null,
  subject_id int not null,
  semester tinyint not null,
  academic_year char(9) not null,
  primary key (teacher_id, subject_id, semester, academic_year),
  foreign key (teacher_id) references teacher(teacher_id),
  foreign key (subject_id) references subject(subject_id),
  foreign key (academic_year) references academic_year(academic_year)
);

create table user (
  email varchar(50) primary key,
  password varchar(255) not null,
  role varchar(10) not null,
);
-- role: admin, teacher
