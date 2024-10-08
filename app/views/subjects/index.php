<?php

use App\utils\Helper;

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/nav.php';
?>

<div id="main" class="">
    <div class="row">
        <div class="col-3 border-end">
            <form action="/subjects/store" enctype="multipart/form-data" method="post" id="subject_form">
                <span class="fw-bold" style="font-size: 1rem;">Thông tin Môn học</span>
                <input type="hidden" name="subject_id" id="subject_id" value="-1">
                <div class="mb-1">
                    <label for="subject_name" class="form-label mb-0">Tên môn học <span style="color:red;">*</span></label>
                    <input type="text" class="form-control" id="subject_name" name="subject_name">
                    <p class="text-danger text-end"><?= Helper::getFormErrorFromSession('subject_name') ?></p>
                </div>
                <div class="mb-1">
                    <label for="grade" class="form-label mb-0">Khối <span style="color:red;">*</span></label>
                    <select class="form-select" id="grade" name="grade">
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
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

            <!-- Thêm button cho phép them cùng lúc nhiều dữ liệu từ file excel -->
            <div class="d-flex mt-5">
                <a href="/excel" class="ms-auto px-3 btn btn-sm btn-outline-success">
                    Xuất ra file excel
                </a>
            </div>
        </div>
        <div class="col-9">
            <!-- Hiển thị thông tin tất cả học sinh từ cơ sở dữ liệu kèm theo 2 button xem, sửa và xóa (sử dụng fa icon), ẩn địa chỉ-->
            <!-- Hiển thị thanh filter và search -->
            <form action="/subjects" method="GET">
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
                            <input type="text" class="form-control" placeholder="Tìm theo tên môn" name="subject_name">
                        </div>
                    </div>
                    <div class="col-1 offset-4">
                        <button type="submit" class="btn btn-outline-primary" type="button" id="button-addon2">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            <table class="table table-striped table-hover mt-2">
                <thead>
                    <tr>
                        <th scope="col">Mã môn</th>
                        <th scope="col">Tên môn</th>
                        <th scope="col">Khối</th>
                        <th scope="col" class="text-end ms-auto">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subjects as $subject) : ?>
                        <tr class="subject">
                            <td class="subject_id"><?= $subject['subject_id'] ?></td>
                            <td class="subject_name"><?= $subject['subject_name'] ?></td>
                            <td class="grade"><?= $subject['grade'] ?></td>
                            <td class="text-end ms-auto">

                                <button class="btn btn-sm btn-outline-warning edit_btn border-0 py-0" type="submit">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <form action="/subjects/delete" class="d-inline" method="POST">
                                    <input hidden type="text" name="subject_id" value="<?= Helper::htmlEscape($subject['subject_id']) ?>">
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
</div>

<script>
    const fields = {
        'subject_id': 'subject_id',
        'subject_name': 'subject_name',
        'grade': 'grade',
    };
    const formId = 'subject_form';
    const trClass = 'subject';
</script>


<?php require_once __DIR__ . '/../partials/footer.php'; ?>