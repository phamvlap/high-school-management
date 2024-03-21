<?php

require __DIR__ . '/../app/bootstrap.php';

$router = new \Bramus\Router\Router();

$router->setNamespace('\App\controllers');

// auth
require_once __DIR__ . '/../app/routes/auth.php';
// classes
require_once __DIR__ . '/../app/routes/classes.php';
// marks
require_once __DIR__ . '/../app/routes/marks.php';
// // rooms
require_once __DIR__ . '/../app/routes/rooms.php';
// // statistics
require_once __DIR__ . '/../app/routes/statistics.php';
// // students
require_once __DIR__ . '/../app/routes/students.php';
// // teachers
require_once __DIR__ . '/../app/routes/teachers.php';
// // errors
require_once __DIR__ . '/../app/routes/errors.php';

$router->run();
