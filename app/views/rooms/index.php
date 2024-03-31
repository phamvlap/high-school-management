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
                <input type="text" id="room_id" name="room_id" hidden>
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
                <form action="/rooms" class="d-flex">
                    <div class="ms-1 mb-1">
                        <label for="min_capacity" class="form-label mb-0">Sức chứa tối thiểu</label>
                        <select class="form-select" id="min_capacity" name="min_capacity">
                            <option value="none" <?= ($_GET['min_capacity'] === 'none') ? 'selected' : '' ?>>Sức chứa</option>
                            <option value="10" <?= ((int)$_GET['min_capacity'] === 10) ? 'selected' : '' ?>>10</option>
                            <option value="15" <?= ((int)$_GET['min_capacity'] === 15) ? 'selected' : '' ?>>15</option>
                        </select>
                    </div>
                    <div class="ms-1 mb-1">
                        <label for="max_capacity" class="form-label mb-0">Sức chứa tối đa</label>
                        <select class="form-select" id="max_capacity" name="max_capacity">
                            <option value="none" <?= ($_GET['max_capacity'] === 'none') ? 'selected' : '' ?>>Sức chứa</option>
                            <option value="40" <?= ((int)$_GET['max_capacity'] === 40) ? 'selected' : '' ?>>40</option>
                            <option value="60" <?= ((int)$_GET['max_capacity'] === 60) ? 'selected' : '' ?>>60</option>
                        </select>
                    </div>
                    <div class="ms-1 mb-1">
                        <label for="sort" class="form-label mb-0">Sắp xếp theo sức chứa</label>
                        <select class="form-select" id="sort" name="sort">
                            <option value="none" <?= ($_GET['sort'] === 'none') ? 'selected' : '' ?>>-- Chọn --</option>
                            <option value="1" <?= ((int)$_GET['sort'] === 1) ? 'selected' : '' ?>>Tăng dần</option>
                            <option value="0" <?= ((int)$_GET['sort'] === 0) ? 'selected' : '' ?>>Giảm dần</option>
                            <!--<option value="dob-asc">Ngày sinh tăng dần</option>
                            <option value="dob-desc">Ngày sinh giảm dần</option>-->
                        </select>
                    </div>
                    <div class="ms-1 mb-1">
                        <label for="limit" class="form-label mb-0">Hiển thị</label>
                        <select class="form-select" id="limit" name="limit">
                            <option value="none" <?= ($_GET['limit'] === 'none') ? 'selected' : '' ?>>Số hàng</option>
                            <option value="10" <?= ((int)$_GET['limit'] === 10) ? 'selected' : '' ?>>10</option>
                            <option value="20" <?= ((int)$_GET['limit'] === 20) ? 'selected' : '' ?>>20</option>
                            <option value="25" <?= ((int)$_GET['limit'] === 25) ? 'selected' : '' ?>>25</option>
                        </select>
                    </div>
                    <div class="ms-1 mb-1 d-flex align-items-end">
                        <button class="btn btn-sm btn-outline-primary">Lọc</button>
                    </div>
                </form>

                <div class="mt-2 align-self-center">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Tìm kiếm"
                            aria-label="Recipient's username" aria-describedby="button-addon2">
                        <button class="btn " type="button" id="button-addon2">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <table class="table table-striped table-hover mt-2">
                <thead>
                    <tr>
                        <th scope="col">STT</th>
                        <th scope="col">Số phòng</th>
                        <th scope="col">Sức chứa</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach($rooms as $room): ?>
                        <tr class="room">
                            <td scope="row" class="room_id">
                                <?= Helper::htmlEscape($room['room_id']) ?>
                            </td>
                            <td class="room_number"><?= Helper::htmlEscape($room['room_number']) ?></td>
                            <td class="maximum_capacity"><?= Helper::htmlEscape($room['maximum_capacity']) ?></td>
                            <td>
                                <button class="btn btn-sm edit_btn">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <form action="/rooms/delete" method="POST" class="d-inline">
                                    <input type="text" name="room_id" value="<?= Helper::htmlEscape($room['room_id']) ?>" hidden>
                                    <button class="btn btn-sm">
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