<?php

/**
 * getRoute retourne la route utilisée pour accéder à une certaine méthode à partir de son nom.
 * C'est un design pattern de factory.
 * En effet elle retourne une méthode d'un contrôleur en fonction des paramètres.
 * @param $routeName string Le nom de la route sans le nom du contrôleur
 * @return string Le nom complet de la route
 */
function getRoute(string $routeName): string {
    $routes = \Config\Services::routes();
    $routes = $routes->getRoutes();
    foreach ($routes as $route) {
        if (str_contains($route, $routeName)) {
            $position = strpos("\App\Controllers\AdminController::adminView", '/');
            return substr($route, 0, ($position != 0 ? $position : strlen($route)));
        }
    }
    return '\App\Controllers\Home::index';
}