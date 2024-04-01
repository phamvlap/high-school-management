<?php

use App\utils\Helper;

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/nav.php';

?>

<div id="main" class="">
    <div class="row">
        <div class="col-3 border-end">
            <form action="/teachers/store" method="POST" id="teacher_form">
                <span class="fw-bold" style="font-size: 1rem;">Thông tin giáo viên</span>
                <input type="hidden" name="teacher_id" id="teacher_id" value="-1">
                <div class="mb-1">
                    <label for="full_name" class="form-label mb-0">Họ và tên<span style="color: red;"> *</span></label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?= Helper::getFormDataFromSession('full_name') ?>">
                    <p class="text-danger text-end"><?= Helper::getFormErrorFromSession('full_name') ?></p>
                </div>
                <div class="mb-1">
                    <label for="date_of_birth" class="form-label mb-0">Ngày sinh<span style="color: red;"> *</span></label>
                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?= Helper::getFormDataFromSession('date_of_birth') ?>">
                    <p class="text-danger text-end"><?= Helper::getFormErrorFromSession('date_of_birth') ?></p>
                </div>
                <div class="mb-1">
                    <label for="address" class="form-label mb-0">Địa chỉ<span style="color: red;"> *</span></label>
                    <input type="text" class="form-control" id="address" name="address" value="<?= Helper::getFormDataFromSession('address') ?>">
                    <p class="text-danger text-end"><?= Helper::getFormErrorFromSession('address') ?></p>
                </div>
                <div class="mb-1">
                    <label for="phone_number" class="form-label mb-0">Số điện thoại<span style="color: red;"> *</span></label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?= Helper::getFormDataFromSession('phone_number') ?>">
                    <p class="text-danger text-end"><?= Helper::getFormErrorFromSession('phone_number') ?></p>
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
                    Xuất ra excel
                </a>
            </div>
        </div>
        <div class="col-9">
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
                    <div class="col-2">
                        <label for="sort" class="form-label mb-0">Sắp xếp</label>
                        <?php $sort = $_GET['sort'] ?? 'none'; ?>
                        <select class="form-select" id="sort" name="sort">
                            <option value="none" <?= ($sort === 'none') ? 'selected' : '' ?>>Không</option>
                            <option value="1" <?= ($sort === '1') ? 'selected' : '' ?>>Tên(A-Z)</option>
                            <option value="0" <?= ($sort === '0') ? 'selected' : '' ?>>Tên(Z-A)</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Tìm kiếm theo tên" name="full_name">
                        </div>
                    </div>
                    <div class="col-3">
                        <input type="text" class="form-control" placeholder="Tìm kiếm theo địa chỉ" name="address">
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
                        <tr class="teacher">
                            <td class="teacher_id"><?= Helper::htmlEscape($teacher['teacher_id']) ?></td>
                            <td class="full_name"><?= Helper::htmlEscape($teacher['full_name']) ?></td>
                            <td class="date_of_birth"><?= Helper::htmlEscape($teacher['date_of_birth']) ?></td>
                            <td class="phone_number"><?= Helper::htmlEscape($teacher['phone_number']) ?></td>
                            <td class="address"><?= Helper::htmlEscape($teacher['address']) ?></td>
                            <td class="text-xs">
                                <button class="btn btn-outline-warning btn-sm border-0 py-0 edit_btn">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <form action="/teachers/delete" class="d-inline" method="POST">
                                    <input hidden type="text" name="teacher_id" value="<?= Helper::htmlEscape($teacher['teacher_id']) ?>">
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
        'teacher_id': 'teacher_id',
        'full_name': 'full_name',
        'date_of_birth': 'date_of_birth',
        'phone_number': 'phone_number',
        'address': 'address'
    };
    const formId = 'teacher_form';
    const trClass = 'teacher';
</script>

<?php

require_once __DIR__ . '/../partials/footer.php';
