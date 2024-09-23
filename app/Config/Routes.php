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

    // Route untuk 'program-studi'
    $routes->group('program-studi', function($routes) {
        $routes->get('/', 'ProgramStudiController::index');
        $routes->get('create', 'ProgramStudiController::create');
        $routes->post('store', 'ProgramStudiController::store');
        $routes->get('edit/(:num)', 'ProgramStudiController::edit/$1');
        $routes->put('update/(:num)', 'ProgramStudiController::update/$1');
        $routes->delete('delete/(:num)', 'ProgramStudiController::delete/$1');
    });

    // Route untuk 'kelas'
    $routes->group('kelas', function($routes) {
        $routes->get('/', 'KelasController::index');
        $routes->get('create', 'KelasController::create');
        $routes->post('store', 'KelasController::store');
        $routes->get('edit/(:num)', 'KelasController::edit/$1');
        $routes->put('update/(:num)', 'KelasController::update/$1');
        $routes->delete('delete/(:num)', 'KelasController::delete/$1');
    });

    // Route untuk 'mahasiswa'
    $routes->group('mahasiswa', function($routes) {
        $routes->get('/', 'MahasiswaController::index');
        $routes->get('create', 'MahasiswaController::create');
        $routes->post('store', 'MahasiswaController::store');
        $routes->get('edit/(:num)', 'MahasiswaController::edit/$1');
        $routes->put('update/(:num)', 'MahasiswaController::update/$1');
        $routes->delete('delete/(:num)', 'MahasiswaController::delete/$1');

        // Route untuk dropdown
        $routes->get('dropdown/getKelasByProgramStudi/(:num)', 'MahasiswaController::getKelasByProgramStudi/$1');
    });

    // Route untuk 'waktu-kuliah'
    $routes->group('waktu-kuliah', function($routes) {
        $routes->get('/', 'WaktuKuliahController::index');
        $routes->get('create', 'WaktuKuliahController::create');
        $routes->post('store', 'WaktuKuliahController::store');
        $routes->get('edit/(:num)', 'WaktuKuliahController::edit/$1');
        $routes->put('update/(:num)', 'WaktuKuliahController::update/$1');
        $routes->delete('delete/(:num)', 'WaktuKuliahController::delete/$1');
    });

    // Route untuk 'periode-kuliah'
    $routes->group('periode-kuliah', function($routes) {
        $routes->get('/', 'PeriodeKuliahController::index');
        $routes->get('create', 'PeriodeKuliahController::create');
        $routes->post('store', 'PeriodeKuliahController::store');
        $routes->get('edit/(:num)', 'PeriodeKuliahController::edit/$1');
        $routes->put('update/(:num)', 'PeriodeKuliahController::update/$1');
        $routes->delete('delete/(:num)', 'PeriodeKuliahController::delete/$1');
    });

    // Route untuk 'jadwal'
    $routes->group('jadwal', function($routes) {
        $routes->get('/', 'JadwalController::index');
        $routes->get('create', 'JadwalController::create');
        $routes->post('store', 'JadwalController::store');
        $routes->get('edit/(:num)', 'JadwalController::edit/$1');
        $routes->put('update/(:num)', 'JadwalController::update/$1');
        $routes->delete('delete/(:num)', 'JadwalController::delete/$1');

        // Generate Jadwal
        $routes->get('generate', 'JadwalController::generateView');
        $routes->post('generate', 'JadwalController::generate');
        $routes->post('generate/store', 'JadwalController::generateStore');
        $routes->post('generate/conflict/getConflictUpdateData', 'JadwalController::getConflictUpdateData');
        $routes->post('generate/conflict/checkConflictIndividual', 'JadwalController::checkConflictIndividual');

        // Route untuk dropdown
        $routes->get('dropdown/getOptionsByProgramStudi/(:num)', 'JadwalController::getOptionsByProgramStudi/$1');
    });
});



