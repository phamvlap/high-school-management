<?php

$router->mount('/classes', function() use ($router) {
	$router->get('/', 'ClassController@index');
	$router->get('/create', 'ClassController@create');
	$router->post('/store', 'ClassController@store');
	$router->get('/(\d+)/edit', 'ClassController@edit');
	$router->post('/(\d+)/update', 'ClassController@update');
	$router->post('/(\d+)/delete', 'ClassController@delete');
});
