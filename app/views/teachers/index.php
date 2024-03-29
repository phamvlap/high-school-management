<?php

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/nav.php';

?>

<div id="main" class="">
    <div class="row">
        <div class="col-3 border-end">
            <form action="/teachers/store" method="POST">
                <span class="fw-bold" style="font-size: 1rem;">Thông tin giáo viên</span>
                <input type="hidden" name="teacher_id" id="teacher_id" value="-1">
                <div class="mb-1">
                    <label for="teacher_name" class="form-label mb-0">Họ và tên<span style="color: red;"> *</span></label>
                    <input type="text" class="form-control" id="teacher_name" name="teacher_name">
                </div>
                <div class="mb-1">
                    <label for="teacher_dob" class="form-label mb-0">Ngày sinh<span style="color: red;"> *</span></label>
                    <input type="date" class="form-control" id="teacher_dob" name="teacher_dob">
                </div>
                <div class="mb-1">
                    <label for="teacher_address" class="form-label mb-0">Địa chỉ<span style="color: red;"> *</span></label>
                    <input type="text" class="form-control" id="teacher_address" name="teacher_address">
                </div>
                <div class="mb-1">
                    <label for="teacher_phone" class="form-label mb-0">Số điện thoại<span style="color: red;"> *</span></label>
                    <input type="text" class="form-control" id="teacher_phone" name="teacher_phone">
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
            <form class="d-flex justify-content-between">
                <div class="d-flex">
                    <div class="mb-1">
                        <label for="teacher_dob_sort" class="form-label mb-0">Lọc theo năm sinh</label>
                        <br>
                        <input type="date" class="form-control" name="teacher_dob_sort" id="teacher_dob_sort">
                    </div>
                    <div class="ms-1 mb-1">
                        <label for="school-year" class="form-label mb-0">Lọc theo năm học</label>
                        <select class="form-select" id="school-year" name="school-year">
                            <option selected>Năm học</option>
                            <option value="2023-2024">2023-2024</option>
                            <option value="2024-2025">2024-2025</option>
                            <option value="2025-2026">2025-2026</option>
                        </select>
                    </div>
                    <div class="ms-1 mb-1">
                        <label for="sort" class="form-label mb-0">Sắp xếp</label>
                        <select class="form-select" id="sort" name="sort">
                            <option selected>Họ tên</option>
                            <option value="name-asc">Tên A-Z</option>
                            <option value="name-desc">Tên Z-A</option>
                            <!--<option value="dob-asc">Ngày sinh tăng dần</option>
                                    <option value="dob-desc">Ngày sinh giảm dần</option>-->
                        </select>
                    </div>
                </div>
                <div class="mt-2 align-self-center">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Tìm kiếm" aria-label="Recipient's username" aria-describedby="button-addon2">
                        <button class="btn btn-outline-primary" type="button" id="button-addon2">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            <table class="table table-striped table-hover mt-2">
                <thead>
                    <tr>
                        <th scope="col">Mã số CB</th>
                        <th scope="col">Họ và tên</th>
                        <th scope="col">Ngày sinh</th>
                        <th scope="col">Số điện thoại</th>
                        <th scope="col">Địa chỉ</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($teachers as $teacher) : ?>
                        <tr>
                            <td><?= $teacher['teacher_id'] ?></td>
                            <td><?= $teacher['full_name'] ?></td>
                            <td><?= $teacher['date_of_birth'] ?></td>
                            <td><?= $teacher['phone_number'] ?></td>
                            <td><?= $teacher['address'] ?></td>
                            <td>
                                <button class="btn btn-sm ">
                                    <i class="fa fa-eye"></i>
                                </button>
                                <button class="btn btn-sm ">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm ">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>


            <!-- Thêm phân trang -->
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

<?php

require_once __DIR__ . '/../partials/footer.php';
