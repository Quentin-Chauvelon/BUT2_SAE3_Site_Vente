<?php

function getRoute($routeName) {
    $routes = \Config\Services::routes(true);
    $routes = $routes->getRoutes();
    foreach ($routes as $route) {
        if ($route['name'] == $routeName) {
            return $route['as'];
        }
    }
    return null;
}