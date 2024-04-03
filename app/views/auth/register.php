<?php

use App\utils\Helper;

require_once __DIR__ . '/../partials/header.php';

?>

<div id="main" class="main--auth d-flex justify-content-center align-items-center">
    <div class="card auth-form">
        <div class="card-header">
            <h4 class="text-center text-primary">
                ĐĂNG KÝ
            </h4>
        </div>
        <div class="card-body">
            <form action="/register/submit" method="POST">
                <div class="mb-3">
                    <label for="phone_number" class="form-label fw-bold">Số điện thoại:</label>
                    <input type="phone_number" class="form-control" id="phone_number" name="phone_number" required value="<?= Helper::getFormDataFromSession('phone_number') ?>">
                    <p class="text-danger text-end">
                        <?= Helper::getFormErrorFromSession('phone_number') ?>
                    </p>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">Mật khẩu</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <p class="text-danger text-end">
                        <?= Helper::getFormErrorFromSession('password') ?>
                    </p>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label fw-bold">Nhập lại mật khẩu</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    <p class="text-danger text-end">
                        <?= Helper::getFormErrorFromSession('confirm_password') ?>
                    </p>
                </div>
                <div class="text-end">
                    <a href="/login" class="d-flex-inline me-4">
                        Đăng nhập ngay
                    </a>
                    <button type="submit" class="btn btn-info">Đăng ký</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php

require_once __DIR__ . '/../partials/footer.php';
