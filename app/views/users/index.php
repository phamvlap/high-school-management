<?php

use App\utils\Helper;

require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/nav.php';

?>

<div id="main" class="">
    <div class="row">
        <div class="col-3 border-end">
            <form action="/users/store" method="POST" id="user_form">
                <span class="fw-bold" style="font-size: 1rem;">Thông tin nguời dùng</span>
                <div class="mb-1">
                    <label for="username" class="form-label mb-0">Tên tài khoản<span style="color: red;"> *</span></label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= Helper::getFormDataFromSession('username') ?>">
                    <p class="text-danger text-end">
                        <?= Helper::getFormErrorFromSession('username') ?>
                    </p>
                </div>
                <div class="mb-1">
                    <label for="password" class="form-label mb-0">Mật khẩu<span style="color: red;"> *</span></label>
                    <input type="text" class="form-control" id="password" name="password" value="<?= Helper::getFormDataFromSession('password') ?>">
                    <p class="text-danger text-end">
                        <?= Helper::getFormErrorFromSession('password') ?>
                    </p>
                </div>
                <div class="mb-1">
                    <label for="type" class="form-label mb-0">Loại người dùng<span style="color: red;"> *</span></label>
                    <select class="form-select" name="type">
                        <option value="admin" <?= Helper::getFormDataFromSession('type') === 'admin' ? 'selected' : '' ?>>admin</option>
                        <option value="teacher" <?= Helper::getFormDataFromSession('type') === 'teacher' ? 'selected' : '' ?>>teacher</option>
                        <option value="parent" <?= Helper::getFormDataFromSession('type') === 'parent' ? 'selected' : '' ?>>parent</option>
                    </select>
                    <p class="text-danger text-end">
                        <?= Helper::getFormErrorFromSession('type') ?>
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
                <a href="/excel" class="ms-auto px-3 btn btn-sm btn-outline-success">
                    Xuất ra file excel
                </a>
            </div>
        </div>
        <div class="col-9">
            <form action="/users">
                <div class="row align-items-end">
                    <div class="col-3">
                        <label for="type" class="form-label mb-0">Loại người dùng</label>
                        <select class="form-select" name="type">
                            <option value="" <?= (!empty($_GET['type']) && $_GET['type'] === null) ? 'selected' : '' ?>>Tất cả</option>
                            <option value="admin" <?= (!empty($_GET['type']) && $_GET['type'] === 'admin') ? 'selected' : '' ?>>admin</option>
                            <option value="teacher" <?= (!empty($_GET['type']) && $_GET['type'] === 'teacher') ? 'selected' : '' ?>>teacher</option>
                        </select>
                    </div>

                    <div class="col-3">
                        <input type="text" class="form-control" placeholder="Tìm theo tên tài khoản" name="username" value="<?= isset($_GET['username']) ? htmlspecialchars($_GET['username']) : '' ?>">
                    </div>

                    <div class="col-1 offset-5">
                        <button class="btn btn-outline-primary " type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>

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
                    <?php foreach ($users as $user) : ?>
                        <tr class="user">
                            <td scope="row" class="username"><?= Helper::htmlEscape($user['username']) ?></td>
                            <td class="type"><?= Helper::htmlEscape($user['type']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-warning border-0 py-0 edit_btn">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <form action="/users/delete" method="POST" class="d-inline">
                                    <input type="text" name="username" value="<?= Helper::htmlEscape($user['username']) ?>" hidden>
                                    <button class="btn btn-sm btn-outline-danger border-0 py-0">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <?php require __DIR__ . '/../partials/pagination.php'; ?>
        </div>
    </div>
</div>

<script>
    const fields = {
        'username': 'username',
        'type': 'type',
    };
    const formId = 'user_form';
    const trClass = 'user';
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>