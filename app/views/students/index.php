<?php

use App\utils\Helper;

require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/nav.php';

?>
<div id="main" class="">
    <div class="row">
        <div class="col-3 border-end">
            <form action="/students/store" method="POST" id="student_form">
                <span class="fw-bold" style="font-size: 1rem;">Nhập thông tin học sinh</span>
                <input type="hidden" name="student_id" id="student_id" value="-1">
                <div class="mb-1">
                    <label for="full_name" class="form-label mb-0">Họ và tên</label>
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
                <a href="/excel" class="ms-auto px-3 btn btn-sm btn-outline-success">
                    Xuất ra file excel
                </a>
            </div>
        </div>
        <div class="col-9">
        <form action="/students" method="GET">
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
                <div class="col-2">
                    <label for="is_order_by_name" class="form-label mb-0">Sắp xếp</label>
                    <?php $is_order_by_name = $_GET['is_order_by_name'] ?? 'none'; ?>
                    <select class="form-select" id="is_order_by_name" name="is_order_by_name">
                        <option value="none" <?= ($is_order_by_name === 'none') ? 'selected' : '' ?>>Chọn</option>
                        <option value="1" <?= ($is_order_by_name === '1') ? 'selected' : '' ?>>Theo tên</option>
                        <option value="0" <?= ($is_order_by_name === '0') ? 'selected' : '' ?>>Tên mã</option>
                    </select>
                </div>
                <div class="col-2">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Tên" name="full_name">
                    </div>
                </div>
                <div class="col-2">
                    <input type="text" class="form-control" placeholder="Địa chỉ" name="address">
                </div>
                <div class="col-2">
                    <input type="text" class="form-control" placeholder="Năm học" name="academic_year">
                </div>
                <div class="col-1">
                    <button type="submit" class="btn btn-outline-primary" type="button" id="button-addon2">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </form>

            <table class="table table-striped table-hover mt-2">
                <thead>
                    <tr>
                        <th scope="col">Mã</th>
                        <th scope="col">Họ và tên</th>
                        <th scope="col">Ngày sinh</th>
                        <th scope="col" >Địa chỉ</th>
                        <th scope="col">SĐT cha mẹ</th>
                        <th scope="col">Mã lớp</th>
                        <th scope="col">Năm học</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($students as $student) : ?>
                        <tr class="student">
                            <td scope="row" class="student_id"><?= Helper::htmlEscape($student['student_id']) ?></td>
                            <td class="full_name"><?= Helper::htmlEscape($student['full_name']) ?></td>
                            <td class="date_of_birth"><?= Helper::htmlEscape($student['date_of_birth']) ?></td>
                            <td class="address"><?= Helper::htmlEscape($student['address']) ?></td>
                            <td class="parent_phone_number"><?= Helper::htmlEscape($student['parent_phone_number']) ?></td>
                            <td class="class_id"><?= Helper::htmlEscape($student['class_id']) ?></td>
                            <td class="academic_year"><?= Helper::htmlEscape($student['academic_year']) ?></td>
                            <td class="text-xs">
                                <button class="btn btn-outline-warning btn-sm border-0 py-0 edit_btn">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <form action="/students/delete" method="POST" class="d-inline">
                                    <input type="text" name="student_id" value="<?= Helper::htmlEscape($student['student_id']) ?>" hidden>
                                    <button class="btn btn-outline-danger btn-sm border-0 py-0">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <?php require_once __DIR__ . '/../partials/pagination.php'; ?>
        </div>
    </div>
</div>

<script>
    const fields = {
        'student_id' : 'student_id',
        'full_name' : 'full_name',
        'date_of_birth' : 'date_of_birth',
        'address' : 'address',
        'parent_phone_number' : 'parent_phone_number',
        'class_id' : 'class_id',
        'academic_year' : 'academic_year'
    };
    const formId = 'student_form';
    const trClass = 'student';
</script>

<?php

require __DIR__ . '/../partials/footer.php';

?>