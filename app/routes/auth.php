<?php

$router->get('/register', 'AuthController@createRegister');
$router->post('/register/submit', 'AuthController@register');
$router->get('/login', 'AuthController@createLogin');
$router->post('/login/submit', 'AuthController@login');
$router->post('/logout', 'AuthController@logout');
