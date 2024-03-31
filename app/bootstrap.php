<?php

define('APP_DIR', __DIR__);
define('MAX_RECORDS_PER_PAGE', 10);

require __DIR__ . '/../vendor/autoload.php';

session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
