<?php

define('APP_DIR', __DIR__);
define('MAX_RECORDS_PER_PAGE', 10);
define('UPLOAD_DIR', __DIR__ . '/../public/uploads/');
<<<<<<< HEAD
define('MAX_LIMIT', 100000000);
=======
define('MAX_LIMIT', 100000);
>>>>>>> e6e9b51064ca6db0fdddc989b73ac59232503d33

require __DIR__ . '/../vendor/autoload.php';

session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
