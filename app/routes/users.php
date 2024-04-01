<?php

$router->mount('/users', function() use ($router) {
	$router->get('/', 'AccountController@index');
	$router->post('/store', 'AccountController@store');
	$router->post('/delete', 'AccountController@delete');
});

