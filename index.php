<?php
/**
 * AgriLink - Entry Point (Front Controller)
 * PHP Native MVC
 */

// 1. Muat konfigurasi global
require_once __DIR__ . '/config/config.php';

// 2. Muat koneksi database
require_once __DIR__ . '/config/Database.php';

// 3. Muat Core Application
require_once __DIR__ . '/core/App.php';

// 4. Inisialisasi dan jalankan router
$app = new App();
$app->run();
