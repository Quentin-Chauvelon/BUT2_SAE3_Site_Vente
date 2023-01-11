<?php

/**
 * getRoute retourne la route utilisée en fonction de la méhode utilisée (get ou post).
 * Elle retourne la route si elle a été trouvée avec la méthode donnée.
 * @param $routeName string Le nom de la route sans le nom du contrôleur
 * @param $method string La méthode a uitlisée pour chercher la route (get, post)
 * @return string Le nom complet de la route
 */
function getRouteByMethod(string $routeName, string $method)
{
    $routes = \Config\Services::routes();
    $routes = $routes->getRoutes($method);

    foreach ($routes as $route) {
        if (str_contains($route, $routeName)) {
            $position = strpos($route, '/');
            return substr($route, 0, ($position != 0 ? $position : strlen($route)));
        }
    }

    return NULL;
}

/**
 * getRoute retourne la route utilisée pour accéder à une certaine méthode à partir de son nom.
 * C'est un design pattern de factory.
 * En effet elle retourne une méthode d'un contrôleur en fonction des paramètres.
 * @param $routeName string Le nom de la route sans le nom du contrôleur
 * @return string Le nom complet de la route
 */
function getRoute(string $routeName): string {
    $route = getRouteByMethod($routeName, 'get');

    if (!$route) {
        $route = getRouteByMethod($routeName, 'post');
    }

    if (!$route) {
        return '\App\Controllers\Home::index';
    }

    return $route;
}