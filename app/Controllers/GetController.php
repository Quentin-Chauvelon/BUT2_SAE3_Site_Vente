<?php

function getRoute($routeName) {
    $routes = \Config\Services::routes(true);
    $routes = $routes->getRoutes();
    foreach ($routes as $route) {
        //var_dump($route);
        if (str_contains($route, $routeName)) {
            return substr($route, strpos($route, '/'));
        }
    }
    return null;
}
