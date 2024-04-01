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
	delete from homeroom_teachers
	where class_id = _class_id;
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
