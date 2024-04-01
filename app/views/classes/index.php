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
                    <input type="text" class="form-control" id="class_name" name="class_name" value="<?= Helper::getFormDataFromSession('class_name') ?>">
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
                <a href="/excel" class="ms-auto px-3 btn btn-sm btn-outline-success">
                    Xuất ra file excel
                </a>
            </div>
        </div>


        <div class="col-9">
            <form action="/classes" method="GET">
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
                        <label for="grade" class="form-label mb-0">Khối</label>
                        <?php $grade = $_GET['grade'] ?? 'all'; ?>
                        <select class="form-select" id="grade" name="grade">
                            <option value="" <?= ($grade === 'all') ? 'selected' : '' ?>>Tất cả</option>
                            <option value="10" <?= ($grade === '10') ? 'selected' : '' ?>>10</option>
                            <option value="11" <?= ($grade === '11') ? 'selected' : '' ?>>11</option>
                            <option value="12" <?= ($grade === '12') ? 'selected' : '' ?>>12</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Tìm theo tên lớp" name="class_name">
                        </div>
                    </div>
                    <div class="col-3">
                        <input type="text" class="form-control" placeholder="Tìm theo năm học" name="academic_year">
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
                                    <button type="submit" class="btn btn-outline-danger btn-sm onclick=" deleteClass(<?= Helper::htmlEscape($class['class_id']) ?>)">
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
                };
                const formId = 'class_form';
                const trClass = 'class';
            </script>
            <?php

            require_once __DIR__ . '/../partials/footer.php';
