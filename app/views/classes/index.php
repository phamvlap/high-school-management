
<?php

use App\utils\Helper;

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/nav.php';

?><div id="main" class="">
<div class="row">
    <div class="col-3 border-end">
        <form action="">
            <span class="fw-bold" style="font-size: 1rem;">Thông tin lớp học</span>
            <div class="mb-1">
                <label for="class_name" class="form-label mb-0">Tên lớp<span style="color: red;"> *</span></label>
                <input type="text" class="form-control" id="class_name" name="class_name">
            </div>
            <div class="mb-1">
                <label for="room_name" class="form-label mb-0">Phòng học<span style="color: red;"> *</span></label>
                <input type="text" class="form-control" id="room_name" name="room_name">
            </div>
            <div class="mb-1">
                <label for="homeroom_teacher_name" class="form-label mb-0">Giáo viên chủ nhiệm<span style="color: red;"> *</span></label>
                <input type="text" class="form-control" id="homeroom_teacher_name" name="homeroom_teacher_name">
            </div>
            <div class="mb-1 row">
                <span class="col">
                    <label for="semester" class="form-label mb-0">Học kỳ<span style="color: red;"> *</span></label>
                    <select class="form-select" id="semester" name="semester">
                        <option selected>Học kỳ</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select>
                </span>
                <span class="col">
                    <label for="academic_year" class="form-label mb-0">Năm học<span style="color: red;"> *</span></label>
                    <input type="text" class="form-control" id="academic_year" name="academic_year">
                </span>
            </div>
            <div class="d-flex mt-2">
                <button type="reset" class="ms-auto me-2 px-3 btn btn-sm btn-outline-danger">
                    Hủy
                </button>
                <button type="submit" class="px-3 btn btn-sm btn-outline-primary">
                    Lưu
                </button>
            </div>
        </form>

        <!-- Thêm button cho phép them cùng lúc nhiều dữ liệu từ file excel -->
        <div class="d-flex mt-5">
            <button class="ms-auto px-3 btn btn-sm btn-outline-success">
                Thêm từ file excel
            </button>
        </div>
    </div>
    <div class="col-9">
        <!-- Hiển thị thông tin tất cả học sinh từ cơ sở dữ liệu kèm theo 2 button xem, sửa và xóa (sử dụng fa icon), ẩn địa chỉ-->
        <!-- Hiển thị thanh filter và search -->
        <form class="d-flex justify-content-between" style="width: 100%;" >
            <div class="d-flex">
                <div class="mb-1">
                    <label for="semester_filter" class="form-label mb-0">Lọc theo học kỳ</label>
                    <select class="form-select" id="semester_filter" name="semester_filter">
                        <option selected>Học kỳ</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select>
                </div>
                <div class="ms-1 mb-1">
                    <label for="school-year" class="form-label mb-0">Lọc theo năm học</label>
                    <select class="form-select" id="school-year" name="school-year">
                        <option selected>Năm học</option>
                        <option value="2024-2025">2024-2025</option>
                        <option value="2025-2026">2025-2026</option>
                    </select>
                </div>
                <div class="ms-1 " style="float: right;">
                    <label for="sort" class="form-label mb-0">Sắp xếp</label>
                    <select class="form-select" id="sort" name="sort">
                        <option selected>Tên lớp</option>
                        <option value="name-asc">Tên A-Z</option>
                        <option value="name-desc">Tên Z-A</option>
                        <!--<option value="dob-asc">Ngày sinh tăng dần</option>
                        <option value="dob-desc">Ngày sinh giảm dần</option>-->
                    </select>
                </div>
            </div>
            <div class="mt-2 align-self-center">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Tìm kiếm"
                        aria-label="Recipient's username" aria-describedby="button-addon2">
                    <button class="btn btn-outline-primary" type="button" id="button-addon2">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </form>

        <table class="table table-striped table-hover mt-2">
            <thead>
                <tr>
                    <th scope="col">STT</th>
                    <th scope="col">Tên lớp</th>
                    <th scope="col">Phòng học</th>
                    <th scope="col">Giáo viên chủ nhiệm</th>
                    <th scope="col">Học kỳ</th>
                    <th scope="col">Năm học</th>
                    <th scope="col" class="ms-auto">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dòng dữ liệu 1 -->
                <?php foreach ($classes as $class) : ?>
                <tr>
                    <td><?= $class['class_id'] ?></td>
                    <td><?= $class['class_name'] ?></td>
                    <td><?= $class['room_number'] ?></td>
                    <td><?= $class['full_name'] ?></td>
                    <td><?= $class['semester'] ?></td>
                    <td><?= $class['academic_year'] ?></td>
                    <td>
                        <button class="btn btn-sm  border-0">
                            <i class="fa fa-eye"></i>
                        </button>
                        <button class="btn btn-sm  border-0">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-sm  border-0">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">1</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">2</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">3</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>


    </div>
</div>
</div>

<!-- <script>
    const fields = {
        'subject_id': 'subject_id',
        'subject_name': 'subject_name',
        'grade': 'grade',
    };
    const formId = 'subject_form';
    const trClass = 'subject';
</script> -->


<?php require_once __DIR__ . '/../partials/footer.php'; ?>