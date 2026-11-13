<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rute Autentikasi
$routes->get('login', 'Auth::login');         // Menampilkan halaman login
$routes->post('login/process', 'Auth::process'); // Menangani proses submit login (POST)
$routes->get('/', 'Auth::login');  // Mengarah ke halaman login / default
$routes->get('logout', 'Auth::logout');

// Rute Panel Admin & Kasir (POS)
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function ($routes) {

    // Dashboard (Masuk subfolder Transaksi)
    $routes->get('dashboard', 'Transaksi\DashboardController::index');

    // Manajemen Layanan (Masuk subfolder Master)
    $routes->get('services', 'Master\ServiceController::index');
    $routes->post('services/create', 'Master\ServiceController::create');
    $routes->post('services/update/(:num)', 'Master\ServiceController::update/$1');
    $routes->get('services/delete/(:num)', 'Master\ServiceController::delete/$1');

    $routes->get('services/categories', 'Master\ServiceCategoryController::index');
    $routes->post('services/categories/create', 'Master\ServiceCategoryController::create');
    $routes->get('services/categories/delete/(:num)', 'Master\ServiceCategoryController::delete/$1');
    $routes->post('services/categories/update/(:num)', 'Master\ServiceCategoryController::update/$1');

    $routes->get('services/commissions', 'Master\ServiceCommissionController::index');
    $routes->post('services/commissions/update/(:num)', 'Master\ServiceCommissionController::update/$1');

    // Manajemen Produk (Masuk subfolder Master)
    $routes->get('products', 'Master\ProductController::index');
    $routes->post('products/create', 'Master\ProductController::create');
    $routes->post('products/update/(:num)', 'Master\ProductController::update/$1');
    $routes->get('products/delete/(:num)', 'Master\ProductController::delete/$1');
    $routes->get('products/get-batches/(:num)', 'Master\ProductController::getBatches/$1');

    // Manajemen Penerimaan Produk (Inventory)
    $routes->get('products/receiving', 'Master\ProductPurchaseController::index');
    $routes->post('products/receiving/store', 'Master\ProductPurchaseController::store');
    $routes->get('products/receiving/confirm/(:num)', 'Master\ProductPurchaseController::confirm/$1');


    // Route Manajemen Stok Opname
    $routes->get('products/stock-opname', 'Master\StockOpname::index');
    $routes->get('products/stock-opname/start', 'Master\StockOpname::start');
    $routes->get('products/stock-opname/process/(:num)', 'Master\StockOpname::process/$1');
    $routes->post('products/stock-opname/store/(:num)', 'Master\StockOpname::store/$1'); // <-- Perhatikan bagian ini!
    $routes->get('products/stock-opname/detail/(:num)', 'Master\StockOpname::detail/$1');

    $routes->get('products/expenses', 'Master\Expenses::index');
    $routes->post('products/expenses/store', 'Master\Expenses::store');
    $routes->post('products/expenses/update/(:num)', 'Master\Expenses::update/$1'); // <-- Tambahkan baris ini
    $routes->get('products/expenses/delete/(:num)', 'Master\Expenses::delete/$1');

    // Route Manajemen Waste Produk
    $routes->get('products/waste', 'Master\Waste::index');
    $routes->post('products/waste/store', 'Master\Waste::store');
    $routes->post('products/waste/update/(:num)', 'Master\Waste::update/$1'); // <-- Tambahkan baris ini
    $routes->get('products/waste/delete/(:num)', 'Master\Waste::delete/$1');

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

    // Manajemen Pengguna & Logs
    $routes->get('users', 'User\UserController::index');
    $routes->post('users/store', 'User\UserController::store');
    $routes->post('users/update/(:num)', 'User\UserController::update/$1');
    $routes->get('users/delete/(:num)', 'User\UserController::delete/$1');

    // Logs, Roles, & Settings
    $routes->get('users/logs', 'User\LogController::index');
    $routes->get('users/roles', 'User\RoleController::index');
    $routes->post('users/roles/update/(:segment)', 'User\RoleController::updatePermission/$1');
    $routes->get('users/settings', 'User\SettingController::index');
});
