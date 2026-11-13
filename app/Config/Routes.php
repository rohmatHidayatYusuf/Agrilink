<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --------------------------------------------------------------------
// Public & Authentication Routes
// --------------------------------------------------------------------
$routes->get('/', 'Auth::index');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::login');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::register');
$routes->get('logout', 'Auth::logout');
$routes->get('demo-login/(:segment)', 'Auth::demoLogin/$1');

// --------------------------------------------------------------------
// Protected Routes (Harus Login)
// --------------------------------------------------------------------
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    // Dashboard terpadu
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('langganan', 'Langganan::index');

    // ----------------------------------------------------------------
    // Role: Petani
    // ----------------------------------------------------------------
    $routes->group('', ['filter' => 'role:petani'], static function ($routes) {
        // Lahan & Rencana Tanam
        $routes->get('lahan', 'Lahan::index');
        $routes->post('lahan/simpan', 'Lahan::simpan');
        $routes->post('lahan/rencana', 'Lahan::simpanRencana');
        $routes->get('lahan/arsip/(:num)', 'Lahan::arsipLahan/$1');

        // Estimasi Kebutuhan & Modal
        $routes->get('estimasi', 'Estimasi::index');
        $routes->post('estimasi/simpan', 'Estimasi::simpan');

        // Marketplace Saprotan
        $routes->get('saprotan', 'Saprotan::index');
        $routes->post('saprotan/pesan', 'Saprotan::buatPesanan');

        // Pencatatan Panen
        $routes->get('panen', 'Panen::index');
        $routes->post('panen/simpan', 'Panen::simpan');
        $routes->post('panen/jual', 'Panen::terbitkanListing');

        // Pasar Hasil Tani
        $routes->get('pasar', 'Pasar::index');

        // Pesanan & Transaksi
        $routes->get('pesanan', 'Pesanan::index');
        $routes->post('pesanan/update-status', 'Pesanan::updateStatus');
    });

    // ----------------------------------------------------------------
    // Role: Supplier
    // ----------------------------------------------------------------
    $routes->group('supplier', ['filter' => 'role:supplier'], static function ($routes) {
        $routes->get('katalog', 'Saprotan::katalogSupplier');
        $routes->post('produk/simpan', 'Saprotan::simpanProduk');
        $routes->get('pesanan', 'Pesanan::supplierPesanan');
        $routes->post('pesanan/update-status', 'Pesanan::updateStatus');
    });

    // ----------------------------------------------------------------
    // Role: Buyer
    // ----------------------------------------------------------------
    $routes->group('buyer', ['filter' => 'role:buyer'], static function ($routes) {
        $routes->get('katalog', 'Pasar::katalogBuyer');
        $routes->post('pesan', 'Pasar::buatPesanan');
        $routes->get('pesanan', 'Pesanan::buyerPesanan');
        $routes->post('pesanan/update-status', 'Pesanan::updateStatus');
    });

    // ----------------------------------------------------------------
    // Role: Admin
    // ----------------------------------------------------------------
    $routes->group('admin', ['filter' => 'role:admin'], static function ($routes) {
        $routes->get('users', 'Admin::users');
        $routes->post('users/toggle/(:num)', 'Admin::toggleUserStatus/$1');
        $routes->get('moderasi', 'Admin::moderasi');
        $routes->post('moderasi/toggle/(:segment)/(:num)', 'Admin::toggleModerasi/$1/$2');
        $routes->get('audit', 'Admin::audit');
    });
});
