<?php

$router->mount('/marks', function() use ($router) {
	$router->get('/', 'MarkController@index');
	$router->post('/store', 'MarkController@store');
	$router->post('/delete', 'MarkController@delete');
});