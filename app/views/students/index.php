<?php

use App\utils\Helper;

require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/nav.php';

?>
<div id="main" class="">
    <div class="row">
        <div class="col-3 border-end">
            <form action="/students/store" method="POST">
                <span class="fw-bold" style="font-size: 1rem;">Nhập thông tin học sinh</span>
                <input type="text" id="student_id" name="student_id" hidden>
                <div class="mb-1">
                    <label for="full_name" class="form-label mb-0">Họ tên</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?= Helper::getFormDataFromSession('full_name') ?>">
                    <p class="text-danger text-end">
                        <?= Helper::getFormErrorFromSession('full_name') ?>
                    </p>
                </div>
                <div class="mb-1">
                    <label for="date_of_birth" class="form-label mb-0">Ngày sinh</label>
                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?= Helper::getFormDataFromSession('date_of_birth') ?>">
                    <p class="text-danger text-end">
                        <?= Helper::getFormErrorFromSession('date_of_birth') ?>
                    </p>
                </div>
                <div class="mb-1">
                    <label for="address" class="form-label mb-0">Địa chỉ</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?= Helper::getFormDataFromSession('address') ?>">
                    <p class="text-danger text-end">
                        <?= Helper::getFormErrorFromSession('address') ?>
                    </p>
                </div>
                <div class="mb-1">
                    <label for="parent_phone_number" class="form-label mb-0">Số điện thoại phụ huynh</label>
                    <input type="text" class="form-control" id="parent_phone_number" name="parent_phone_number" value="<?= Helper::getFormDataFromSession('parent_phone_number') ?>">
                    <p class="text-danger text-end">
                        <?= Helper::getFormErrorFromSession('parent_phone_number') ?>
                    </p>
                </div>
                <div class="mb-1">
                    <label for="class_id" class="form-label mb-0">Mã lớp</label>
                    <input type="text" class="form-control" id="class_id" name="class_id" value="<?= Helper::getFormDataFromSession('class_id') ?>">
                    <p class="text-danger text-end">
                        <?= Helper::getFormErrorFromSession('class_id') ?>
                    </p>
                </div>
                <div class="mb-1">
                    <label for="academic_year" class="form-label mb-0">Năm học</label>
                    <input type="text" class="form-control" id="academic_year" name="academic_year" value="<?= Helper::getFormDataFromSession('academic_year') ?>">
                    <p class="text-danger text-end">
                        <?= Helper::getFormErrorFromSession('academic_year') ?>
                    </p>
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
            <div class="d-flex justify-content-between">
                <form action="/students" class="d-flex">
                    <div class="ms-1 mb-1">
                        <label for="academic-year" class="form-label mb-0">Lọc theo niên khóa</label>
                        <select class="form-select" id="academic-year" name="academic-year">
                            <option selected>Niên khóa</option>
                            <option value="2024-2025">2024-2025</option>
                            <option value="2025-2026">2025-2026</option>
                        </select>
                    </div>
                    <div class="ms-1 mb-1">
                        <label for="is_sort_by_name_desc" class="form-label mb-0">Sắp xếp họ tên</label>
                        <select class="form-select" id="is_sort_by_name_desc" name="is_sort_by_name_desc">
                            <option selected>Thứ tự</option>
                            <option value="name-asc">Tên A-Z</option>
                            <option value="name-desc">Tên Z-A</option>
                        </select>
                    </div>
                    <div class="ms-1 mb-1 d-flex align-items-end">
                        <button class="btn btn-sm btn-outline-primary">Lọc</button>
                    </div>
                </form>
                <div class="mt-2 align-self-center">
                    <form action="/students" class="d-flex">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Họ tên">
                        </div>
                        <div class="input-group ms-2">
                            <input type="text" class="form-control" placeholder="Địa chỉ">
                        </div>
                        <div class="input-group ms-2">
                            <input type="text" class="form-control" placeholder="Mã lớp">
                        </div>
                        <button class="btn" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            <table class="table table-striped table-hover mt-2">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Họ tên</th>
                        <th scope="col">Ngày sinh</th>
                        <th scope="col">Số điện thoại phụ huynh</th>
                        <th scope="col">Tên lớp</th>
                        <th scope="col">Năm học</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>Nguyễn Văn A</td>
                        <td>01/01/2001</td>
                        <td>0123456789</td>
                        <td>10A1</td>
                        <td>2023-2024</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Chi tiết">
                                <i class="fa fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Chỉnh sửa">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Xóa">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>Nguyễn Văn B</td>
                        <td>01/01/2002</td>
                        <td>0123456789</td>
                        <td>10A2</td>
                        <td>2023-2024</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning">
                                <i class="fa fa-edit">
                                </i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">3</th>
                        <td>Nguyễn Văn C</td>
                        <td>01/01/2003</td>
                        <td>0123456789</td>
                        <td>10A3</td>
                        <td>2023-2024</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
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

<?php

require __DIR__ . '/../partials/footer.php';

?>