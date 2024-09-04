<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', function() {
    return redirect()->to('/login');
});

$routes->get('/login', 'AuthController::login', ['filter' => 'guest']);
$routes->post('/loginPost', 'AuthController::loginPost', ['filter' => 'guest']);

$routes->post('/logout', 'AuthController::logout', ['filter' => 'auth']);

// Kelompok rute untuk dashboard dengan filter auth
$routes->group('dashboard', ['filter' => 'auth'], function($routes) {
    // Route utama dashboard
    $routes->get('/', 'DashboardController::index');

    // Route untuk 'mata-kuliah'
    $routes->group('mata-kuliah', function($routes) {
        $routes->get('/', 'MataKuliahController::index');
        $routes->get('create', 'MataKuliahController::create');
        $routes->post('store', 'MataKuliahController::store');
        $routes->get('edit/(:num)', 'MataKuliahController::edit/$1');
        $routes->put('update/(:num)', 'MataKuliahController::update/$1');
        $routes->delete('delete/(:num)', 'MataKuliahController::delete/$1');
    });

    // Route untuk 'dosen'
    $routes->group('dosen', function($routes) {
        $routes->get('/', 'DosenController::index');
        $routes->get('create', 'DosenController::create');
        $routes->post('store', 'DosenController::store');
        $routes->get('edit/(:num)', 'DosenController::edit/$1');
        $routes->put('update/(:num)', 'DosenController::update/$1');
        $routes->delete('delete/(:num)', 'DosenController::delete/$1');
    });

    // Route untuk 'ruangan'
    $routes->group('ruangan', function($routes) {
        $routes->get('/', 'RuanganController::index');
        $routes->get('create', 'RuanganController::create');
        $routes->post('store', 'RuanganController::store');
        $routes->get('edit/(:num)', 'RuanganController::edit/$1');
        $routes->put('update/(:num)', 'RuanganController::update/$1');
        $routes->delete('delete/(:num)', 'RuanganController::delete/$1');
    });
});



