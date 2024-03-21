<?php

$router->mount('/students', function() use ($router) {
	$router->get('/', 'StudentController@index');
	$router->get('/create', 'StudentController@create');
	$router->post('/store', 'StudentController@store');
	$router->get('/(\d+)/edit', 'StudentController@edit');
	$router->post('/(\d+)/update', 'StudentController@update');
	$router->post('/(\d+)/delete', 'StudentController@delete');
});