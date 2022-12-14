<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

$routes->get('product/(:num)', 'Product::display/$1');
$routes->get('product', 'Product::display');
$routes->get('products', 'Product::displayAll');
$routes->get('products/categories/(:any)', 'Product::trouverToutDeCategorie/$1');

$routes->get('monCompte', 'ClientController::monCompte');
$routes->get('inscription', 'ClientController::inscription');
$routes->get('connexion', 'ClientController::connexion');
$routes->get('deconnexion', 'ClientController::deconnexion');
$routes->get('afficherFavoris', 'ClientController::afficherFavoris');
$routes->get('ajouterFavori/(:num)/(:any)', 'ClientController::ajouterFavori/$1/$2');
$routes->get('afficherPanier', 'ClientController::afficherPanier');
$routes->get('afficherHistorique', 'ClientController::afficherHistorique');
$routes->get('supprimerDuPanier/(:num)/(:any)/(:any)', 'ClientController::supprimerDuPanier/$1/$2/$3');


$routes->post('creerCompte','ClientController::creerCompte');
$routes->post('connexionCompte','ClientController::connexionCompte');
$routes->post('modifierProfil','ClientController::modifierProfil');
$routes->post('ajouterAuPanier', 'ClientController::ajouterAuPanier');