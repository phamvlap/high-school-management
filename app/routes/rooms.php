<?php

$router->mount('/rooms', function() use ($router) {
	$router->get('/', 'RoomController@index');
	$router->post('/store', 'RoomController@store');
	$router->post('/delete', 'RoomController@delete');
});