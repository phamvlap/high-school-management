<?php

use App\utils\Helper;


require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/nav.php';
?>

<div id="main" class="">
    <div class="row">
        <div class="col-3 border-end">
            <form action="/classes/store" method="POST" id="class_form">
                <span class="fw-bold" style="font-size: 1rem;">Thông tin lớp học</span>
                <input type="hidden" name="class_id" id="class_id" value="-1">
                <div class="mb-1">
                    <label for="class_name" class="form-label mb-0">Tên lớp<span style="color: red;"> *</span></label>
                    <input type="text" class="form-control" id="class_name" name="class_name"value="<?= Helper::getFormDataFromSession('class_name') ?>">
                    <p class="text-danger text-end"><?= Helper::getFormErrorFromSession('class_name') ?></p>
                </div>
                <div class="mb-1">
                    <label for="room_id" class="form-label mb-0">Mã phòng<span style="color: red;"> *</span></label>
                    <input type="text" class="form-control" id="room_id" name="room_id" value="<?= Helper::getFormDataFromSession('room_id') ?>">
                    <p class="text-danger text-end"><?= Helper::getFormErrorFromSession('room_id') ?></p>
                </div>
                <div class="mb-1">
                    <label for="teacher_id" class="form-label mb-0">Giáo viên chủ nhiệm<span style="color: red;"> *</span></label>
                    <input type="text" class="form-control" id="teacher_id" name="teacher_id" value="<?= Helper::getFormDataFromSession('teacher_id') ?>">
                    <p class="text-danger text-end"><?= Helper::getFormErrorFromSession('teacher') ?></p>
                </div>
                <div class="mb-1 row">
                    <span class="col">
                        <label for="semester" class="form-label mb-0">Học kỳ<span style="color: red;"> *</span></label>
                        <select class="form-select" id="semester" name="semester" value="<?= Helper::getFormDataFromSession('semester') ?>">
                            <option value="1" selected>1</option>
                            <option value="2">2</option>
                        </select>
                        <p class="text-danger text-end"><?= Helper::getFormErrorFromSession('semester') ?></p>
                        </span>
                        <span class="col">
                            <label for="academic_year" class="form-label mb-0">Năm học<span style="color: red;"> *</span></label>
                             <input type="text" class="form-control" id="academic_year" name="academic_year" value="<?= Helper::getFormDataFromSession('academic_year') ?>">
                             <p class="text-danger text-end"><?= Helper::getFormErrorFromSession('academic_year') ?></p>
                        </span>
                </div>
                <div class="d-flex mt-3">
                    <button type="reset" class="ms-auto me-2 px-3 btn btn-sm btn-outline-danger">
                        Hủy
                    </button>
                    <button type="submit" class="px-3 btn btn-sm btn-outline-primary">
                        Lưu
                    </button>
                </div>
            </form>

            <div class="d-flex mt-5">
                <button class="ms-auto px-3 btn btn-sm btn-outline-success">
                    Thêm từ file excel
                </button>
            </div>
        </div>

       
        <div class="col-9">
            <form class="d-flex justify-content-between">
                <div class="d-flex">
                    <div class="ms-1 mb-1">
                        <label for="class_semester" class="form-label mb-0">Lọc theo học kỳ</label>
                        <select class="form-select" id="class_semester" name="class_semester">
                            <option selected>Học kỳ</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
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
                        <th scope="col">Mã lớp</th>
                        <th scope="col">Tên lớp</th>
                        <th scope="col" style="display: none;">Mã giáo viên</th>
                        <th scope="col">Giáo viên chủ nhiệm</th>
                        <th scope="col" style="display: none;">Mã phòng</th>
                        <th scope="col">Phòng học</th>
                        <th scope="col">Học kỳ</th>
                        <th scope="col">Năm học</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($classes as $class) : ?>
                        <tr class="class">
                            <td class="class_id"><?= Helper::htmlEscape((string)$class['class_id']) ?></td>
                            <td class="class_name"><?= Helper::htmlEscape((string)$class['class_name']) ?></td>
                            <td class="teacher_id" style="display: none;"><?= Helper::htmlEscape((string)$class['teacher_id']) ?></td>
                            <td class="full_name"><?= Helper::htmlEscape((string)$class['full_name']) ?></td>
                            <td class="room_id" style="display: none;"><?= Helper::htmlEscape((string)$class['room_id']) ?></td>
                            <td class="room_number"><?= Helper::htmlEscape((string)$class['room_number']) ?></td>
                            <td class="semester"><?= Helper::htmlEscape((string)$class['semester']) ?></td>
                            <td class="academic_year"><?= Helper::htmlEscape((string)$class['academic_year']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-warning edit_btn">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <form action="/classes/delete" class="d-inline" method="POST">
                                    <input hidden type="text" name="class_id" value="<?= Helper::htmlEscape($class['class_id']) ?>">
                                    <input hidden type="text" name="teacher_id" value="<?= Helper::htmlEscape($class['teacher_id']) ?>">
                                    <input hidden type="text" name="room_id" value="<?= Helper::htmlEscape($class['room_id']) ?>">
                                    <input hidden type="text" name="semester" value="<?= Helper::htmlEscape($class['semester']) ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-sm onclick="deleteClass(<?= Helper::htmlEscape($class['class_id'])?>)">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>

            <?php require_once __DIR__ . '/../partials/pagination.php'; ?>

    <script>
        const fields = {
            'class_id': 'class_id',
            'class_name': 'class_name',
            'room_id': 'room_id',
            'teacher_id': 'teacher_id',
            'semester': 'semester',
            'academic_year': 'academic_year',
            'room_number': 'room_number',
            'full_name': 'full_name'
        };
        const formId = 'class_form';
        const trClass = 'class';
    </script>
<?php

require_once __DIR__ . '/../partials/footer.php';


