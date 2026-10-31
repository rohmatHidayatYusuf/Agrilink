<?php
/**
 * Konfigurasi Aplikasi AgriLink
 * PHP Native MVC
 */

// Mulai sesi jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Konfigurasi Database MySQL
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'agrilink_db');

// Nama Aplikasi
define('APP_NAME', 'AgriLink');
define('APP_DESC', 'Sistem Manajemen Usaha Pertanian Terintegrasi');

// Penentuan Base URL Otomatis
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));

// Hapus subpath views/login atau subfolder lain jika file diakses langsung
$baseDir = preg_replace('#/(views|controllers|models|config|core)(/.*)?$#i', '', $scriptDir);
$baseUrl = rtrim($protocol . $host . $baseDir, '/');

define('BASE_URL', $baseUrl);

// Path direktori utama
define('ROOT_PATH', dirname(__DIR__));
