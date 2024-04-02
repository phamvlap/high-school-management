<?php

$router->get('/guest', 'GuestController@index');
$router->post('/guest/submit', 'GuestController@getMarks');