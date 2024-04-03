<?php

use App\utils\Helper;

require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/nav.php';

?>

<div id="main" class="">
    <div class="row">
        <div class="col-3 border-end">
            <form action="/rooms/store" method="POST" id="room_form">
                <span class="fw-bold" style="font-size: 1rem;">Thông tin phòng học</span>
                <input type="text" id="room_id" name="room_id" value="-1" hidden>
                <div class="mb-1">
                    <label for="room_number" class="form-label mb-0">Phòng học<span style="color: red;"> *</span></label>
                    <input type="text" class="form-control" id="room_number" name="room_number" value="<?= Helper::getFormDataFromSession('room_number') ?>">
                    <p class="text-danger text-end">
                        <?= Helper::getFormErrorFromSession('room_number') ?>
                    </p>
                </div>
                <div class="mb-1">
                    <label for="maximum_capacity" class="form-label mb-0">Sức chứa<span style="color: red;"> *</span></label>
                    <input type="number" class="form-control" id="maximum_capacity" name="maximum_capacity" value="<?= Helper::getFormDataFromSession('maximum_capacity') ?>">
                    <p class="text-danger text-end">
                        <?= Helper::getFormErrorFromSession('maximum_capacity') ?>
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

            <div class="d-flex mt-5">
                <a href="/excel" target="_blank" class="ms-auto px-3 btn btn-sm btn-outline-success">
                    Xuất ra file excel
                </a>
            </div>
        </div>
        <div class="col-9">
            <div class="d-flex justify-content-between">
                <form action="/rooms" class="row align-items-end">
                    <div class="col-2">
                        <label for="limit" class="form-label mb-0">Hiển thị</label>
                        <select class="form-select" id="limit" name="limit">
                            <?php
                            $limit = (!empty($_GET['limit']) && $_GET['limit'] !== 'all') ? (int)$_GET['limit'] : (int)MAX_RECORDS_PER_PAGE;
                            if (isset($_GET['limit']) && $_GET['limit'] === 'all') {
                                $limit = MAX_LIMIT;
                            }
                            ?>
                            <option value="<?= MAX_LIMIT ?>" <?= ($limit === MAX_LIMIT) ? 'selected' : '' ?>>Tất cả</option>
                            <option value="10" <?= ($limit === 10) ? 'selected' : '' ?>>10</option>
                            <option value="20" <?= ($limit === 20) ? 'selected' : '' ?>>20</option>
                            <option value="50" <?= ($limit === 50) ? 'selected' : '' ?>>50</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <label for="is_sort_by_capacity" class="form-label mb-0">Sắp xếp</label>
                        <select class="form-select" id="is_sort_by_capacity" name="is_sort_by_capacity">
                            <option value="none" <?= (isset($_GET['is_sort_by_capacity']) && $_GET['is_sort_by_capacity'] === 'none') ? 'selected' : '' ?>>-- Chọn --</option>
                            <option value="1" <?= (isset($_GET['is_sort_by_capacity']) && (int)$_GET['is_sort_by_capacity'] === 1) ? 'selected' : '' ?>>Sức chứa tăng dần</option>
                            <option value="0" <?= (isset($_GET['is_sort_by_capacity']) && $_GET['is_sort_by_capacity'] !== 'none' && (int)$_GET['is_sort_by_capacity'] === 0) ? 'selected' : '' ?>>Sức chứa giảm dần</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <label for="min_capacity" class="form-label mb-0">Sức chứa</label>
                        <input type="text" id="min_capacity" name="min_capacity" class="form-control" value="<?= isset($_GET['min_capacity']) ? $_GET['min_capacity'] : '' ?>" placeholder="Tối thiểu">
                    </div>
                    <div class="col-2">
                        <label for="max_capacity" class="form-label mb-0">Sức chứa</label>
                        <input type="text" id="max_capacity" name="max_capacity" class="form-control" value="<?= isset($_GET['max_capacity']) ? $_GET['max_capacity'] : '' ?>" placeholder="Tối đa">
                    </div>

                    <div class="col-3">
                        <input type="text" class="form-control" placeholder="Tìm theo số phòng" name="room_number" value="<?= isset($_GET['room_number']) ? $_GET['room_number'] : '' ?>">
                    </div>
                    <div class="col-1">
                        <button type="submit" class="btn btn-outline-primary" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <table class="table table-striped table-hover mt-2">
                <thead>
                    <tr>
                        <th scope="col">Mã phòng</th>
                        <th scope="col">Số phòng</th>
                        <th scope="col">Sức chứa</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($rooms as $room) : ?>
                        <tr class="room">
                            <td scope="row" class="room_id">
                                <?= Helper::htmlEscape($room['room_id']) ?>
                            </td>
                            <td class="room_number"><?= Helper::htmlEscape($room['room_number']) ?></td>
                            <td class="maximum_capacity"><?= Helper::htmlEscape($room['maximum_capacity']) ?></td>
                            <td>
                                <button class="btn btn-sm edit_btn btn-outline-warning border-0 py-0">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <form action="/rooms/delete" method="POST" class="d-inline">
                                    <input type="text" name="room_id" value="<?= Helper::htmlEscape($room['room_id']) ?>" hidden>
                                    <button class="btn btn-sm btn-outline-danger border-0 py-0">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>

                </tbody>
            </table>

            <script>
                const fields = {
                    'room_id': 'room_id',
                    'room_number': 'room_number',
                    'maximum_capacity': 'maximum_capacity'
                };
                const formId = 'room_form';
                const trClass = 'room';
            </script>
            <?php

            require __DIR__ . '/../partials/pagination.php';

            ?>

        </div>
    </div>
</div>

<?php

require __DIR__ . '/../partials/footer.php';

?>