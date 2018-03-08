<?php

return function (FastRoute\RouteCollector $router) {
    $router->get('/', 'IndexController@indexAction');
    $router->get('/login', 'IndexController@loginAction');
    $router->get('/logout', 'IndexController@logoutAction');
    $router->get('/recommendations', 'IndexController@recommendationsAction');


    $router->get('/admin', 'AdminController@indexAction');
    $router->addRoute(['GET', 'POST'], '/admin/login', 'AdminController@loginAction');
    $router->get('/admin/logout', 'AdminController@logoutAction');
};