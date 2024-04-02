<?php

define('APP_DIR', __DIR__);
define('MAX_RECORDS_PER_PAGE', 10);
define('UPLOAD_DIR', __DIR__ . '/../public/uploads/');
define('MAX_LIMIT', 100000000);
define('SYSTEM_NAME', 'Hệ thống quản lý Trường Trung học phổ thông');

require __DIR__ . '/../vendor/autoload.php';

session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
