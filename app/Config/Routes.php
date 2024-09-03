<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/login', 'AuthController::login');
$routes->post('/loginPost', 'AuthController::loginPost');
$routes->get('/logout', 'AuthController::logout');

$routes->get('/dashboard', 'DashboardController::index');

$routes->group('dashboard/mata-kuliah', function($routes) {
    $routes->get('/', 'MataKuliahController::index');
    $routes->get('create', 'MataKuliahController::create');
    $routes->post('store', 'MataKuliahController::store');
    $routes->get('edit/(:num)', 'MataKuliahController::edit/$1');
    $routes->put('update/(:num)', 'MataKuliahController::update/$1'); // Menggunakan PUT untuk update
    $routes->delete('delete/(:num)', 'MataKuliahController::delete/$1'); // Menggunakan DELETE untuk delete
});