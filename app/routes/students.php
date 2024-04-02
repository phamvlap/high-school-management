<?php

$router->mount('/students', function() use ($router) {
	$router->get('/', 'StudentController@index');
	$router->post('/store', 'StudentController@store');
	$router->post('/delete', 'StudentController@delete');
});