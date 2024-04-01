<?php

$router->mount('/classes', function() use ($router) {
	$router->get('/', 'ClassController@index');
	$router->post('/store', 'ClassController@store');
	$router->post('/(\d+)/update', 'ClassController@update');
	$router->post('/delete', 'ClassController@delete');
});
