<?php

$router->get('/parents', 'GuestController@index');
$router->post('/parents/submit', 'GuestController@getMarks');
