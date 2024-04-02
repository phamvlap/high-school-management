<?php


$router->mount('/roomclass', function () use ($router) {
    $router->get('/', 'RoomClassController@index');
    $router->post('/store', 'RoomClassController@store');
    $router->post('/delete', 'RoomClassController@delete');
});
