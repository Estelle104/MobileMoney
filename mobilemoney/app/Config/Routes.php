<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Home;
use App\Controllers\AchatController;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('/login', 'Home::login');                    // Home gère la vue login
$routes->post('/login/check', 'UserController::checkLogin');

$routes->get('/logout', function() {
    session()->destroy();
    return redirect()->to('/login');
});