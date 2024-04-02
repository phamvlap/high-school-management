<?php

require __DIR__ . '/../app/bootstrap.php';

$router = new \Bramus\Router\Router();

$router->setNamespace('\App\controllers');

// middleware // TODO: (dời các route vào một cấp để sử dụng middleware)
$router->before('GET|POST', '/admin/.*', function () {
    if (!isset($_SESSION['auth'])) {
        \App\utils\Helper::redirectTo('/login');
        return;
    }
});
// guest
require_once __DIR__ . '/../app/routes/guest.php';
// home
require_once __DIR__ . '/../app/routes/home.php';
// auth
require_once __DIR__ . '/../app/routes/auth.php';
// classes
require_once __DIR__ . '/../app/routes/classes.php';
// marks
require_once __DIR__ . '/../app/routes/marks.php';
// rooms
require_once __DIR__ . '/../app/routes/rooms.php';
// room_class
require_once __DIR__ . '/../app/routes/roomclass.php';
// statistics
require_once __DIR__ . '/../app/routes/statistics.php';
// // students
require_once __DIR__ . '/../app/routes/students.php';
// teachers
require_once __DIR__ . '/../app/routes/teachers.php';
//users
require_once __DIR__ . '/../app/routes/users.php';
// subjects
require_once __DIR__ . '/../app/routes/subjects.php';
//excel
$router->get('/excel', 'ExcelController@index');
// errors
require_once __DIR__ . '/../app/routes/errors.php';

$router->run();
