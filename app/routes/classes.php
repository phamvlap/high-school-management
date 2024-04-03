<?php

use App\utils\Helper;

$router->mount('/classes', function() use ($router) {
	$router->get('/', 'ClassController@index');
	$router->post('/store', 'ClassController@store');
	$router->post('/delete', 'ClassController@delete');
});
