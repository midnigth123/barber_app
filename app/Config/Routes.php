<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rute Autentikasi
$routes->get('login', 'Auth::login');         // Menampilkan halaman login
$routes->post('login/process', 'Auth::process'); // Menangani proses submit login (POST)
$routes->get('/', 'Auth::login');  // Mengarah ke method logout() di Auth.php
$routes->get('logout', 'Auth::logout');

// 2. Rute Panel Admin & Kasir (POS)
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function ($routes) {

    // Dashboard (Masuk subfolder Transaksi)
    $routes->get('dashboard', 'Transaksi\DashboardController::index');

    // Manajemen Layanan (Masuk subfolder Master)
    $routes->get('services', 'Master\ServiceController::index');
    $routes->post('services/create', 'Master\ServiceController::create');
    $routes->post('services/update/(:num)', 'Master\ServiceController::update/$1');
    $routes->get('services/delete/(:num)', 'Master\ServiceController::delete/$1');

    // Manajemen Produk (Masuk subfolder Master)
    $routes->get('products', 'Master\ProductController::index');
    $routes->post('products/create', 'Master\ProductController::create');
    $routes->post('products/update/(:num)', 'Master\ProductController::update/$1');
    $routes->get('products/delete/(:num)', 'Master\ProductController::delete/$1');

    // Manajemen POS & Struk (Masuk subfolder Transaksi)
    $routes->get('pos', 'Transaksi\PosController::index');
    $routes->post('pos/store', 'Transaksi\PosController::store');
    $routes->get('pos/receipt/(:num)', 'Transaksi\PosController::receipt/$1');

    // Profil & Password (Masuk subfolder Auth)
    $routes->post('update-profile', 'Auth\ProfileController::updateProfile');
    $routes->post('update-password', 'Auth\ProfileController::updatePassword');

    // Menu Laporan Transaksi
    $routes->get('reports/transactions', 'Transaksi\ReportController::index');
    $routes->get('reports/transaction-detail/(:num)', 'Transaksi\ReportController::getDetail/$1');
    $routes->post('reports/cancel-transaction/(:num)', 'Transaksi\ReportController::cancel/$1');

    // Manajemen Pengguna (Sub-menu User)
    $routes->get('users', 'User\UserController::index');            
    $routes->post('users/store', 'User\UserController::store');      
    $routes->post('users/update/(:num)', 'User\UserController::update/$1'); 
    $routes->get('users/delete/(:num)', 'User\UserController::delete/$1'); // Digunakan untuk Soft Delete / Toggle Status

    // Logs & Roles (Sudah disesuaikan namespace-nya agar tidak duplikat/error 404)
    $routes->get('users/logs', 'User\LogController::index');          
    $routes->get('users/roles', 'User\User\RoleController::index'); // Mengarah ke App\Controllers\Admin\User\User\RoleController atau sesuaikan folder foldernya
    $routes->get('users/settings', 'User\SettingController::index'); 
    $routes->get('users/logs', 'User\LogController::index');
});