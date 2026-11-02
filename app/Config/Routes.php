<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// 1. Rute Halaman Utama / Kasir (POS)
$routes->get('/', 'Cashier::index');


// Rute Autentikasi / Login
$routes->get('login', 'Auth::login');
$routes->post('login/process', 'Auth::process');
$routes->get('logout', 'Auth::logout');

// 2. Grup Rute untuk Panel Admin
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function ($routes) {

    // Dashboard Admin (Opsional jika ingin dibuatkan rutenya)
    $routes->get('dashboard', 'DashboardController::index');

    // Manajemen Layanan (Services) - CRUD Lengkap
    $routes->get('services', 'ServiceController::index');
    $routes->post('services/create', 'ServiceController::create');
    $routes->post('services/update/(:num)', 'ServiceController::update/$1'); // <-- Rute Update yang ditambahkan
    $routes->get('services/delete/(:num)', 'ServiceController::delete/$1');  // <-- Rute Delete yang ditambahkan

    // Manajemen Produk (Products)
    $routes->get('products', 'ProductController::index');
    $routes->post('products/create', 'ProductController::create');
    $routes->post('products/update/(:num)', 'ProductController::update/$1');
    $routes->get('products/delete/(:num)', 'ProductController::delete/$1');

    //Manajmen Pos
    $routes->get('pos', 'PosController::index');
    $routes->post('pos/store', 'PosController::store');
});
