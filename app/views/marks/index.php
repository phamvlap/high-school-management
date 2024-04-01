<?php

use App\utils\Helper;

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/nav.php';

?>
<div id="main" class="">
    <div class="row">
        <div class="col-3 border-end">
            <form action="/marks/store" method="post" id="form_mark">
                <span class="fw-bold mb-5" style="font-size: 1rem;">Nhập điểm học sinh</span>
                <div class="mb-1">
                    <label for="student_id" class="form-label mb-0">Mã số học sinh <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="student_id" name="student_id">
                    <p class="text-danger text-end"><?= Helper::getFormErrorFromSession('student_id') ?></p>
                </div>
                <div class="mb-1">
                    <label for="subject_id" class="form-label mb-0">Mã môn<span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="subject_id" name="subject_id">
                    <p class="text-danger text-end"><?= Helper::getFormErrorFromSession('subject_id') ?></p>
                </div>
                <div class="mb-1">
                    <label for="oral_score" class="form-label mb-0"> Điểm kiểm tra miệng </label>
                    <input type="text" class="form-control" id="oral_score" name="oral_score">
                    <p class="text-danger text-end"><?= Helper::getFormErrorFromSession('oral_score') ?></p>
                </div>
                <div class="mb-1">
                    <label for="_15_minutes_score" class="form-label mb-0">Điểm kiểm tra 15 phút </span></label>
                    <input type="text" class="form-control" id="_15_minutes_score" name="_15_minutes_score">
                    <p class="text-danger text-end"><?= Helper::getFormErrorFromSession('_15_minutes_score') ?></p>
                </div>
                <div class="mb-1">
                    <label for="_1_period_score" class="form-label mb-0">Điểm kiểm tra một tiết</label>
                    <input type="text" class="form-control" id="_1_period_score" name="_1_period_score">
                    <p class="text-danger text-end"><?= Helper::getFormErrorFromSession('_1_period_score') ?></p>
                </div>
                <div class="mb-1">
                    <label for="school-year" class="form-label mb-0">Điểm kiểm tra cuối kỳ </label>
                    <input type="text" class="form-control" id="semester_score" name="semester_score">
                    <p class="text-danger text-end"><?= Helper::getFormErrorFromSession('semester_score') ?></p>
                </div>
                <div class="mb-1">
                    <label for="semester" class="form-label mb-0">Học kì</label>
                    <select class="form-select" id="semester" name="semester">
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select>
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
            <div class="d-flex mt-5">
                <a href="/excel" class="ms-auto px-3 btn btn-sm btn-outline-success">
                    Xủất ra file excel
                </a>
            </div>
        </div>

        <div class="col-9">
            <form action="/marks" method="GET">
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
                        <label for="semester" class="form-label mb-0">Học kì</label>
                        <?php $semester = $_GET['semester'] ?? 'all'; ?>
                        <select class="form-select" id="semester" name="semester">
                            <option value="" <?= ($semester === 'all') ? 'selected' : '' ?>>Tất cả</option>
                            <option value="1" <?= ($semester === '1') ? 'selected' : '' ?>>1</option>
                            <option value="2" <?= ($semester === '2') ? 'selected' : '' ?>>2</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Tìm  theo mã môn" name="subject_id">
                        </div>
                    </div>
                    <div class="col-3">
                        <input type="text" class="form-control" placeholder="Tìm  theo mã học sinh" name="student_id">
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

                        <th scope="col">Mã số</th>
                        <th scope="col">Họ tên</th>
                        <th scope="col">Môn học(mã môn)</th>
                        <th scope="col">Miệng</th>
                        <th scope="col">15 phút</th>
                        <th scope="col">Một tiết</th>
                        <th scope="col">Cuối kì</th>
                        <th scope="col">Học kì</th>
                        <th scope="col">Năm học</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($marks as $mark) : ?>
                        <tr class="mark">
                            <td class="student_id"><?= Helper::htmlEscape($mark['student_id']) ?></td>
                            <td><?= Helper::htmlEscape($mark['full_name']) ?></td>
                            <td class="subject_id"><?= Helper::htmlEscape($mark['subject_name']) ?>(<?= Helper::htmlEscape($mark['subject_id']) ?>)</td>
                            <td class="oral_score"><?= Helper::htmlEscape(!empty($mark['oral_score']) ? $mark['oral_score'] : '--') ?></td>
                            <td class="_15_minutes_score"><?= Helper::htmlEscape(!empty($mark['_15_minutes_score']) ? $mark['_15_minutes_score'] : '--') ?></td>
                            <td class="_1_period_score"><?= Helper::htmlEscape(!empty($mark['_1_period_score']) ? $mark['_1_period_score'] : '--') ?></td>
                            <td class="semester_score"><?= Helper::htmlEscape(!empty($mark['semester_score']) ? $mark['semester_score'] : '--') ?></td>
                            <td class="semester"><?= Helper::htmlEscape($mark['semester']) ?></td>
                            <td class="academic_year"><?= Helper::htmlEscape($mark['academic_year']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-warning edit_btn border-0 py-0">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <form action="/marks/delete" class="d-inline" method="POST">
                                    <input hidden type="text" name="subject_id" value="<?= Helper::htmlEscape($mark['subject_id']) ?>">
                                    <input hidden type="text" name="student_id" value="<?= Helper::htmlEscape($mark['student_id']) ?>">
                                    <input hidden type="text" name="semester" value="<?= Helper::htmlEscape($mark['semester']) ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-sm border-0 py-0">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php require_once __DIR__ . '/../partials/pagination.php'; ?>
        </div>
    </div>
    <script>
        const fields = {
            'student_id': 'student_id',
            'subject_id': 'subject_id',
            'oral_score': 'oral_score',
            '_15_minutes_score': '_15_minutes_score',
            '_1_period_score': '_1_period_score',
            'semester_score': 'semester_score',
            'semester': 'semester',
        };
        const formId = 'form_mark';
        const trClass = 'mark';
    </script>

    <?php require_once __DIR__ . '/../partials/footer.php'; ?>