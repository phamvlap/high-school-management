use high_school_management;
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
call add_class(-1, '10A5', '2023-2024');
call add_class(-1, '11A5', '2022-2023');
call add_class(-1, '12A5', '2023-2024');
call add_class(2, '10A6', '2023-2024');

-- [procedure]: delete_class(_class_id)
-- [author]: phamvlap
drop procedure if exists delete_class $$
create procedure delete_class(
	in _class_id int
)
begin
	set @academic_year_of_class = (
		select academic_year
		from classes
		where class_id = _class_id
	);

	delete from classes
	where class_id = _class_id;
	if not exists (
		select *
		from classes
		where academic_year = @academic_year_of_class
	)
		then
			delete from academic_years
			where academic_year = @academic_year_of_class;
	end if;
end $$

-- [example]:
call delete_class(2);

-- [function]: get_all(_class_name, _grade, _academic_year)
-- [author]: phamvlap
drop procedure if exists get_all $$

create procedure get_all_classes(
	in _class_name varchar(10),
	in _grade varchar(10),
	in _academic_year char(9),
    in _is_order_by_class_name int
)
begin
	select *
	from classes as c
		left join homeroom_teachers as ht
			on c.class_id = ht.class_id 
		left join teachers as t
			on ht.teacher_id = t.teacher_id
	where (_class_name is null or c.class_name like concat('%', _class_name, '%'))
		and (_grade is null or c.class_name like concat('%', _grade, '%'))
		and (_academic_year is null or c.academic_year = _academic_year)
	order by
		case
			when _is_order_by_class_name = 0 then class_name
			when _is_order_by_class_name = 1 then class_name
            else 'class_name'
		end,
		case
			when _is_order_by_class_name = 0 then 'desc'
			when _is_order_by_class_name = 1 then 'asc'
            else ''
		end;
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
	from classes as c
		join homeroom_teachers as ht
			on class_id = ht.class_id 
		join teachers as t
			on ht.teacher_id = t.teacher_id
	where c.class_id = _class_id;
end $$
