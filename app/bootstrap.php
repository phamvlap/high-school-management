<?php

require __DIR__ . '/../vendor/autoload.php';

use App\db\PDOFactory;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
