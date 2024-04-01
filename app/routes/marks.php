<?php

$router->mount('/marks', function() use ($router) {
	$router->get('/', 'MarkController@index');
	$router->get('/create', 'MarkController@create');
	$router->post('/store', 'MarkController@store');
	$router->get('/(\d+)/edit', 'MarkController@edit');
	$router->post('/(\d+)/update', 'MarkController@update');
	$router->post('/delete', 'MarkController@delete');
});