<?php

$router->mount('/teachers', function () use ($router) {
	$router->get('/', 'TeacherController@index');
	$router->post('/store', 'TeacherController@store');
	$router->post('/delete', 'TeacherController@delete');
});
