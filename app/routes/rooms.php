<?php

$router->mount('/rooms', function() use ($router) {
	$router->get('/', 'RoomController@index');
	$router->get('/create', 'RoomController@create');
	$router->post('/store', 'RoomController@store');
	$router->get('/(\d+)/edit', 'RoomController@edit');
	$router->post('/(\d+)/update', 'RoomController@update');
	$router->post('/(\d+)/delete', 'RoomController@delete');
});