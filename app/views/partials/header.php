<!DOCTYPE html>
<html lang="en">
<?php
use App\utils\Helper;
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Language" content="vi">
    <title>Hệ thống quản lý</title>
    <link rel="stylesheet" href="./assets/css/index.css">
    <link href="assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div id="app">
        <div id="header">
            <div class="header-brand">
                <span class="fw-bold text-primary"><?= SYSTEM_NAME ?></span>
            </div>

            <div class="header-homepage">
                <?php if(Helper::isLogged() && $_SESSION['auth']['type'] === 'parent'): ?>
                    <form action="/logout" method="POST" id="logout-form">
                        <button type="submit" class="logout-btn">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            <span>Đăng xuất</span>
                        </button>
                    </form>
                <?php else: ?>
                    <a href="/">
                        <i class="fa fa-home"></i>
                    </a>
                <?php endif; ?>

            </div>
        </div>