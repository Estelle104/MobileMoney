<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

// -------------------------------------------------
// Routes publiques OPERATEUR (pas de filtre)
// -------------------------------------------------
$routes->get('operateur/login', 'Operateur\AuthController::login');
$routes->post('operateur/checklogin', 'Operateur\AuthController::checkLogin');
$routes->get('operateur/logout', 'Operateur\AuthController::logout');

// -------------------------------------------------
// Routes protégées OPERATEUR
// -------------------------------------------------
$routes->group('operateur', ['filter' => 'authOperateur'], function ($routes) {

    $routes->get('dashboard', 'Operateur\DashboardController::index');


    // Configuration des préfixes
    $routes->get('configuration', 'Operateur\PrefixeController::index');
    $routes->get('configuration/list', 'Operateur\PrefixeController::list');
    $routes->get('configuration/creer', 'Operateur\PrefixeController::creer');
    $routes->post('configuration/enregistrer', 'Operateur\PrefixeController::enregistrer');
    $routes->get('configuration/modifier/(:num)', 'Operateur\PrefixeController::modifier/$1');
    $routes->post('configuration/mettreajour/(:num)', 'Operateur\PrefixeController::mettreajour/$1');
    $routes->post('configuration/supprimer/(:num)', 'Operateur\PrefixeController::supprimer/$1');

    // Configuration des préfixes externes
    $routes->get('prefixe-externe/list', 'Operateur\PrefixeExterneController::list');
    $routes->get('prefixe-externe/creer', 'Operateur\PrefixeExterneController::creer');
    $routes->post('prefixe-externe/enregistrer', 'Operateur\PrefixeExterneController::enregistrer');
    $routes->get('prefixe-externe/modifier/(:num)', 'Operateur\PrefixeExterneController::modifier/$1');
    $routes->post('prefixe-externe/mettreajour/(:num)', 'Operateur\PrefixeExterneController::mettreajour/$1');
    $routes->post('prefixe-externe/supprimer/(:num)', 'Operateur\PrefixeExterneController::supprimer/$1');

    // Types d'opérations / barème de frais
    $routes->get('operation/list', 'Operateur\OperationController::list');
    $routes->get('operation/list/(:num)', 'Operateur\OperationController::list/$1');
    $routes->get('operation/ajouter', 'Operateur\OperationController::ajouter');
    $routes->post('operation/enregistrer', 'Operateur\OperationController::enregistrer');
    $routes->get('operation/modifier/(:num)', 'Operateur\OperationController::modifier/$1');
    $routes->post('operation/update/(:num)', 'Operateur\OperationController::update/$1');
    $routes->post('operation/supprimer/(:num)', 'Operateur\OperationController::supprimer/$1');

    // Gains
    $routes->get('gains', 'Operateur\GainController::index');
    $routes->get('gains/filtrer', 'Operateur\GainController::filtrer');

    // Règlements externes
    $routes->get('reglements-externes', 'Operateur\ReglementExterneController::index');
    $routes->get('reglements-externes/filtrer', 'Operateur\ReglementExterneController::filtrer');
    $routes->get('reglements-externes/creer', 'Operateur\ReglementExterneController::creer');
    $routes->post('reglements-externes/enregistrer', 'Operateur\ReglementExterneController::enregistrer');

    // Comptes clients
    $routes->get('clients/list', 'Operateur\ClientController::list');
    $routes->get('clients/detail/(:num)', 'Operateur\ClientController::detail/$1');
});

// -------------------------------------------------
// Routes publiques CLIENT (pas de filtre)
// -------------------------------------------------
$routes->get('client/login', 'Client\AuthController::login');
$routes->post('client/checklogin', 'Client\AuthController::checkLogin');
$routes->get('client/logout', 'Client\AuthController::logout');

// -------------------------------------------------
// Routes protégées CLIENT
// -------------------------------------------------
$routes->group('client', ['filter' => 'authClient'], function ($routes) {

    $routes->get('dashboard', 'Client\DashboardController::index');
    $routes->get('solde', 'Client\CompteController::solde');

    $routes->get('depot', 'Client\OperationController::depot');
    $routes->post('depot/valider', 'Client\OperationController::validerDepot');

    $routes->get('retrait', 'Client\OperationController::retrait');
    $routes->post('retrait/valider', 'Client\OperationController::validerRetrait');

    $routes->get('transfert', 'Client\OperationController::transfert');
    $routes->post('transfert/valider', 'Client\OperationController::validerTransfert');
    $routes->get('transfert-multiple', 'Client\OperationController::transfertMultiple');
    $routes->post('transfert-multiple/valider', 'Client\OperationController::validerTransfertMultiple');

    $routes->get('historique', 'Client\OperationController::historique');
    
    // AJAX
    $routes->post('calcul-frais', 'Client\OperationController::calculFraisAjax');
});
