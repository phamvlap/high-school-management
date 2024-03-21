<?php

$router->mount('/teachers', function() use ($router) {
	$router->get('/', 'TeacherController@index');
	$router->get('/create', 'TeacherController@create');
	$router->post('/store', 'TeacherController@store');
	$router->get('/(\d+)/edit', 'TeacherController@edit');
	$router->post('/(\d+)/update', 'TeacherController@update');
	$router->post('/(\d+)/delete', 'TeacherController@delete');
});
