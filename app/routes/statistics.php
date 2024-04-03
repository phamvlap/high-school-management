<?php

$router->mount('/statistics', function () use ($router) {
    $router->get('marks', 'StatisticsController@marks');
    $router->get('students', 'StatisticsController@students');
    $router->get('teachers', 'StatisticsController@teachers');
    $router->get('', 'StatisticsController@index');
});
