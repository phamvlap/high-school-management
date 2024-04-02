<?php

use App\utils\Helper;

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/nav.php';

?>
<div id="main" class="">
    <div class="row">
        <div class="col-3 border-end">
            <form action="/roomclass/store" method="POST" id="form_room_class">
                <span class="fw-bold" style="font-size: 1rem;">Thông tin phòng - lớp</span>
                <div class="mb-1">
                    <label for="class_id" class="form-label mb-0">Mã lớp<span style="color: red;"> *</span></label>
                    <input type="number" class="form-control" id="class_id" name="class_id" value="<?= Helper::getFormDataFromSession('class_id') ?>">
                    <p class="text-danger text-end"><?= Helper::getFormErrorFromSession('class_id') ?></p>
                </div>
                <div class="mb-1">
                    <label for="room_id" class="form-label mb-0">Mã phòng<span style="color: red;"> *</span></label>
                    <input type="number" class="form-control" id="room_id" name="room_id" value="<?= Helper::getFormDataFromSession('room_id') ?>">
                    <p class="text-danger text-end"><?= Helper::getFormErrorFromSession('room_id') ?></p>
                </div>
                <div class="mb-1">
                    <label for="semester" class="form-label mb-0">Học kỳ<span style="color: red;"> *</span></label>
                    <select class="form-select" id="semester" name="semester" value="<?= Helper::getFormDataFromSession('semester') ?>">
                        <option value="1" selected>1</option>
                        <option value="2">2</option>
                    </select>
                    <p class="text-danger text-end"><?= Helper::getFormErrorFromSession('semester') ?></p>
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
            <form action="/roomclass" method="GET">
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
                        <label for="limit" class="form-label mb-0">Sắp xếp</label>
                        <?php $sort = $_GET['sort'] ?? 'none'; ?>
                        <select class="form-select" id="sort" name="sort">
                            <option value="-1" <?= ($sort === 'none') ? 'selected' : '' ?>>-- Chọn --</option>
                            <option value="1" <?= ($sort === '1') ? 'selected' : '' ?>>Tên lớp (A-Z)</option>
                            <option value="0" <?= ($sort === '0') ? 'selected' : '' ?>>Tên lớp (Z-A)</option>
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
                    <div class="col-2">
                        <label for="semester" class="form-label mb-0">Học kì</label>
                        <?php $semester = $_GET['semester'] ?? 'all'; ?>
                        <select class="form-select" id="semester" name="semester">
                            <option value="" <?= ($semester === 'all') ? 'selected' : '' ?>>Tất cả</option>
                            <option value="1" <?= ($semester === '1') ? 'selected' : '' ?>>1</option>
                            <option value="2" <?= ($semester === '2') ? 'selected' : '' ?>>2</option>
                        </select>
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
                        <th scope="col">Mã phòng</th>
                        <th scope="col">Số phòng</th>
                        <th scope="col">Mã lớp</th>
                        <th scope="col">Tên lớp</th>
                        <th scope="col">Học kì</th>
                        <th scope="col">Năm học</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($roomClass as $rc) : ?>
                        <tr class="room_class">
                            <td class="room_id"><?= Helper::htmlEscape((string)$rc['room_id']) ?></td>
                            <td class="room_number"><?= Helper::htmlEscape((string)$rc['room_number']) ?></td>
                            <td class="class_id"><?= Helper::htmlEscape((string)$rc['class_id']) ?></td>
                            <td class="class_name"><?= Helper::htmlEscape((string)$rc['class_name']) ?></td>
                            <td class="semester"><?= Helper::htmlEscape((string)$rc['semester']) ?></td>
                            <td class="academic_year"><?= Helper::htmlEscape((string)$rc['academic_year']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-warning edit_btn border-0 py-0">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <form action="/roomclass/delete" class="d-inline" method="POST">
                                    <input hidden type="text" name="class_id" value="<?= Helper::htmlEscape($rc['class_id']) ?>">
                                    <input hidden type="text" name="semester" value="<?= Helper::htmlEscape($rc['semester']) ?>">
                                    <button type="submit" class="btn btn-outline-danger border-0 py-0 btn-sm">
                                        <i class=" fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php require_once __DIR__ . '/../partials/pagination.php'; ?>

    <script>
        const fields = {
            'room_id': 'room_id',
            'class_id': 'class_id',
            'semester': 'semester',
        };
        const formId = 'form_room_class';
        const trClass = 'room_class';
    </script>
</div>
<?php

require_once __DIR__ . '/../partials/footer.php';
