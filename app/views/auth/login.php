<?php

use App\utils\Helper;

require_once __DIR__ . '/../partials/header.php';

?>

<div id="main" class="main--auth d-flex justify-content-center align-items-center">
    <div class="card auth-form">
        <div class="card-header">
            <h4 class="text-center text-primary">
                ĐĂNG NHẬP
            </h4>
        </div>
        <div class="card-body">
            <form action="/login/submit" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label fw-bold">Tài khoản</label>
                    <input type="username" class="form-control" id="username" name="username" required value="<?= Helper::getFormDataFromSession('username') ?>">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">Mật khẩu</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <p class="text-danger text-end">
                        <?= Helper::getFormErrorFromSession('password') ?>
                    </p>
                </div>
                <div class="mb-3">
                    <label for="captcha" class="form-label fw-bold">Mã xác nhận</label>
                    <div class="row align-items-center">
                        <div class="col-4">
                            <img src="<?= $captcha ?>" alt="captcha" class="img-fluid">
                            <input type="text" value="<?= $_SESSION['captcha'] ?>" hidden>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control" id="captcha" name="captcha" required>
                        </div>
                    </div>
                    <p class=" text-danger text-end">
                        <?= Helper::getFormErrorFromSession('captcha') ?>
                    </p>
                </div>
                <div class="text-end">
                    <a href="/register" class="d-flex-inline me-4">
                        Đăng ký tài khoản
                    </a>
                    <button type="submit" class="btn btn-info">Đăng nhập</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php

require_once __DIR__ . '/../partials/footer.php';
