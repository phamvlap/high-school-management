<?php

use App\utils\Helper;

require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/nav.php';

?>

<div id="main" class="">
    <div class="row">
        <div class="col-3 border-end">
            <form action="/students/store" method="POST" id="student_form">
                <span class="fw-bold" style="font-size: 1rem;">Thông tin học sinh</span>
                <div class="mb-1">
                    <label for="full_name" class="form-label mb-0">Tên học sinh<span style="color: red;"> *</span></label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?= Helper::getFormDataFromSession('full_name') ?>">
                    <p class="text-danger text-end">
                        <?= Helper::getFormErrorFromSession('full_name') ?>
                    </p>
                </div>
                <div class="mb-1">
                    <label for="date_of_birth" class="form-label mb-0">Ngày sinh<span style="color: red;"> *</span></label>
                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?= Helper::getFormDataFromSession('date_of_birth') ?>">
                    <p class="text-danger text-end">
                        <?= Helper::getFormErrorFromSession('date_of_birth') ?>
                    </p>
                </div>
                <div class="mb-1">
                    <label for="address" class="form-label mb-0">Địa chỉ<span style="color: red;"> *</span></label>
                    <input type="text" class="form-control" id="address" name="address" value="<?= Helper::getFormDataFromSession('address') ?>">
                    <p class="text-danger text-end">
                        <?= Helper::getFormErrorFromSession('address') ?>
                    </p>
                </div>
                <div class="mb-1">
                    <label for="parent_phone_number" class="form-label mb-0">Số điện thoại phụ huynh<span style="color: red;"> *</span></label>
                    <input type="text" class="form-control" id="parent_phone_number" name="parent_phone_number" value="<?= Helper::getFormDataFromSession('parent_phone_number') ?>">
                    <p class="text-danger text-end">
                        <?= Helper::getFormErrorFromSession('parent_phone_number') ?>
                    </p>
                </div>
                <div class="mb-1">
                    <label for="class_id" class="form-label mb-0">Mã lớp<span style="color: red;"> *</span></label>
                    <input type="text" class="form-control" id="class_id" name="class_id" value="<?= Helper::getFormDataFromSession('class_id') ?>">
                    <p class="text-danger text-end">
                        <?= Helper::getFormErrorFromSession('class_id') ?>
                    </p>
                </div>
                <div class="mb-1">
                    <label for="academic_year" class="form-label mb-0">Năm học<span style="color: red;"> *</span></label>
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
                    Xuất từ file excel
                </button>
            </div>
        </div>
        <div class="col-9">
            <div class="d-flex justify-content-between">
                <form action="/users" class="d-flex">
                    <div class="ms-1 mb-1">
                        <label for="type" class="form-label mb-0">Loại người dùng</label>
                        <select class="form-select"  name="type">
                            <option value="none" <?= (isset($_GET['is_sort_by_capacity']) === 'none') ? 'selected' : '' ?>>-- Chọn --</option>
                            <option value="admin" <?= (isset($_GET['is_sort_by_capacity']) && (int)$_GET['is_sort_by_capacity'] === 1) ? 'selected' : '' ?>>admin</option>
                            <option value="teacher" <?= (isset($_GET['is_sort_by_capacity']) && $_GET['is_sort_by_capacity'] !== 'none' && (int)$_GET['is_sort_by_capacity'] === 0) ? 'selected' : '' ?>>teacher</option>
                        </select>
                    </div>
                    <div class="mt-2 ms-2 align-self-center">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Tìm kiếm"
                                aria-label="Recipient's username" aria-describedby="button-addon2" name="username"
                                value="<?= isset($_GET['username']) ? htmlspecialchars($_GET['username']) : '' ?>">
                            <button class="btn " type="submit" id="button-addon2">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mt-2 ms-2 align-self-center">
                        <div class="input-group">
                            <a href="/users">
                                <button type="button" class="ms-auto me-1 px-3 btn btn-sm btn-primary">
                                    Làm mới
                                </button>
                            </a>
                            
                        </div>
                    </div>
                    
                 </form>

                
            </div>

            <table class="table table-striped table-hover mt-2">
                <thead>
                    <tr>
                        <th scope="col">Username</th>
                        <th scope="col" style="display:none;">Password</th>
                        <th scope="col">Loại người dùng</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user): ?>
                        <tr class="user">
                            <td scope="row" class="username"><?= Helper::htmlEscape($user['username']) ?></td>
                            <td class="password" style="display: none;"><?= Helper::htmlEscape($user['password']) ?></td>
                            <td class="type"><?= Helper::htmlEscape($user['type']) ?></td>
                            <td>
                                <!-- <button class="btn btn-sm edit_btn">
                                    <i class="fa fa-edit"></i>
                                </button> -->
                                <form action="/users/delete" method="POST" class="d-inline">
                                    <input type="text" name="username" value="<?= Helper::htmlEscape($user['username']) ?>" hidden>
                                    <button class="btn btn-sm btn-outline-danger" style="border: 0px;">
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
                    'username': 'username',
                    'password': 'password',
                    'type': 'type',
                    'sorted_type': 'sort_type'
                };
                const formId = 'user_form';
                const trClass = 'user';
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