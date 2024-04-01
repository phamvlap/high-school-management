Cuong Bt
tối là tầm mấy giờ đi dị
Cuong Bt

@Hữu còn quản lí điểm á
<li>
                        <a href="#" class="nav-link link-body-emphasis">
                            <i class="fa-solid fa-pen"></i>
                            Quản lý điểm
                        </a>
                    </li>
Nguyễn Thị Cẩm Tú
mấy h đi z mn @All ?
Khum bít
Hữu
chắc tầm 7h30 đi
Đi đâu
Nguyễn Thị Cẩm Tú
z Nguyên Vũ như cũ đi
Hữu
tui qua trễ xíu nha mn
Nguyễn Thị Cẩm Tú
Có ai qua ch
Tui ts ns r nè
Cuong Bt
Rồi
https://meet.google.com/qpn-rtoo-qcq?pli=1
Hữu
<form action="/teachers" method="GET">
                <div class="row align-items-end">
                    <div class="col-2">
                        <label for="limit" class="form-label mb-0">Hiển thị</label>
                        <?php $limit = $_GET['limit'] ?? '10'; ?>
                        <select class="form-select" id="limit" name="limit">
                            <option value="<?= MAX_LIMIT ?>" <?= ($limit === 'all') ? 'selected' : '' ?>>Tất cả</option>
                            <option value="10" <?= ($limit === '10') ? 'selected' : '' ?>>10</option>
                            <option value="20" <?= ($limit === '20') ? 'selected' : '' ?>>20</option>
                            <option value="50" <?= ($limit === '50') ? 'selected' : '' ?>>50</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Tìm kiếm theo tên" name="full_name">
                        </div>
                    </div>
                    <div class="col-4">
                        <input type="text" class="form-control" placeholder="Tìm kiếm theo địa chỉ" name="address">
                    </div>
                    <div class="col-2">
                        <button type="submit" class="btn btn-outline-primary" type="button" id="button-addon2">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
Hữu
//excel
$router->get('/excel', 'ExcelController@index');
Helper::setIntoSession('download_data', [
                'title' => 'DANH SÁCH GIÁO VIÊN',
                'header' => ['Mã giáo viên', 'Họ và tên', 'Ngày sinh', 'Địa chỉ', 'Số điện thoại'],
                'data' => $teachers
            ]);
Hữu
drop procedure if exists get_all_teachers$$
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
            case when _is_order_by_name = 1 then full_name end asc,
            case when _is_order_by_name = 0 then full_name end desc
        limit _limit
        offset _offset;
end $$
-- [example]: call get_all_teachers(null, null, null, null, 1, 10, 0);

-- [procedure]: get_total_teachers(_full_name, _date_of_birth_start, _date_of_birth_end, _address, is_order_by_name)
-- [author]: cuong
drop procedure if exists get_total_teachers$$
create procedure get_total_teachers(    
    in _full_name varchar(255),
    in _address varchar(255)
)
begin
    select count(*) as total
    from teachers  
    where 
        (_full_name is null or full_name like concat('%',_full_name,'%')) 
        and (_address is null or address like concat('%',_address,'%'));
end $$

call get_total_teachers(null, null);
