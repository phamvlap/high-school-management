<?php

require __DIR__ . '/../app/bootstrap.php';

use App\utils\Helper;

$router = new \Bramus\Router\Router();

$router->setNamespace('\App\controllers');

// middleware // TODO: (dời các route vào một cấp để sử dụng middleware)
$router->before('GET|POST', '/.*', function () {
    $prefixUrl = Helper::getPrefixUrl();
    if(!Helper::isLogged() && $prefixUrl !== 'login' && $prefixUrl !== 'register') {
        Helper::redirectTo('/login');
    }

    // if(Helper::isLogged() && $prefixUrl === 'logout') {
    //     Helper::redirectTo('/logout');
    // }

    // if(Helper::isLogged() && $_SESSION['auth']['type'] === 'parent' && $prefixUrl !== 'parents'){
    //     Helper::redirectTo('/parents');
    // }

    $permittedRoutes = ['', 'home', 'classes', 'marks', 'rooms', 'roomclass', 'statistics', 'students', 'subjects', 'teachers'];

    if(in_array($prefixUrl, $permittedRoutes) && !Helper::isPermitted(['admin', 'teacher'])) {
        Helper::redirectTo('/notpermission');
    }
    else if($prefixUrl === 'users' && !Helper::isPermitted(['admin'])) {
        Helper::redirectTo('/notpermission');
    }
    else if($prefixUrl === 'parents' && !Helper::isPermitted(['parent'])) {
        Helper::redirectTo('/login');
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
