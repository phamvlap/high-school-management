<?php

$router->mount('/subjects', function() use ($router) {
	$router->get('/', 'SubjectController@index');
	$router->get('/create', 'SubjectController@create');
	$router->post('/store', 'SubjectController@store');
	$router->get('/(\d+)/edit', 'SubjectController@edit');
	$router->post('/(\d+)/update', 'SubjectController@update');
	$router->post('/(\d+)/delete', 'SubjectController@delete');
});
