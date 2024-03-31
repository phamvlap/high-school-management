<?php

define('APP_DIR', __DIR__);
define('UPLOAD_DIR', __DIR__ . '/../public/uploads/');

require __DIR__ . '/../vendor/autoload.php';

session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
