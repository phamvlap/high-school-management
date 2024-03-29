<?php

define('APP_DIR', __DIR__);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/utils/helpers.php';

session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
