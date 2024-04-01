<?php

$router->mount('/subjects', function() use ($router) {
	$router->get('/', 'SubjectController@index');
	
	$router->post('/store', 'SubjectController@store');

	$router->post('/delete', 'SubjectController@delete');
});
