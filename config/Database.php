<?php
/**
 * Database Connection & Auto-Migration
 * AgriLink PHP Native MVC
 */

class Database {
    private static ?PDO $instance = null;

    /**
     * Dapatkan koneksi PDO (Singleton)
     */
    public static function getConnection(): PDO {
        if (self::$instance === null) {
            try {
                // 1. Hubungkan ke MySQL Server tanpa memilih DB terlebih dahulu
                $dsnServer = sprintf(
                    'mysql:host=%s;port=%s;charset=utf8mb4',
                    DB_HOST,
                    DB_PORT
                );

                $serverPdo = new PDO($dsnServer, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);

                // 2. Buat database jika belum ada
                $dbName = DB_NAME;
                $serverPdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

                // 3. Hubungkan langsung ke database target
                $dsnDb = sprintf(
                    'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                    DB_HOST,
                    DB_PORT,
                    $dbName
                );

                self::$instance = new PDO($dsnDb, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);

                // 4. Inisialisasi tabel dan data awal jika belum ada
                self::initSchema(self::$instance);

            } catch (PDOException $e) {
                die('<div style="font-family:sans-serif;padding:2rem;background:#fee2e2;color:#991b1b;border:1px solid #fecaca;border-radius:8px;max-width:600px;margin:2rem auto;">'
                    . '<h3>Koneksi Database Gagal!</h3>'
                    . '<p>' . htmlspecialchars($e->getMessage()) . '</p>'
                    . '<p><small>Pastikan MySQL di Laragon / XAMPP sudah berjalan.</small></p>'
                    . '</div>');
            }
        }

        return self::$instance;
    }

    /**
     * Memastikan tabel users dan seed demo siap digunakan
     */
    private static function initSchema(PDO $pdo): void {
        $tableQuery = "
            CREATE TABLE IF NOT EXISTS `users` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `nama_lengkap` VARCHAR(100) NOT NULL,
                `username` VARCHAR(50) NOT NULL UNIQUE,
                `email` VARCHAR(100) NOT NULL UNIQUE,
                `password` VARCHAR(255) NOT NULL,
                `role` ENUM('petani', 'supplier', 'buyer', 'admin') NOT NULL DEFAULT 'petani',
                `telepon` VARCHAR(20) NULL,
                `alamat` TEXT NULL,
                `foto_profil` VARCHAR(255) NULL,
                `status` ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        $pdo->exec($tableQuery);

        // Periksa apakah tabel users masih kosong, jika ya buat user demo
        $stmt = $pdo->query("SELECT COUNT(*) FROM `users`");
        $count = (int) $stmt->fetchColumn();

        if ($count === 0) {
            $defaultPassword = password_hash('password', PASSWORD_BCRYPT);
            $petaniPass = password_hash('petani123', PASSWORD_BCRYPT);
            $supplierPass = password_hash('supplier123', PASSWORD_BCRYPT);
            $buyerPass = password_hash('buyer123', PASSWORD_BCRYPT);
            $adminPass = password_hash('admin123', PASSWORD_BCRYPT);

            $insert = $pdo->prepare("
                INSERT INTO `users` (`nama_lengkap`, `username`, `email`, `password`, `role`, `telepon`, `alamat`)
                VALUES 
                ('Pak Budi Santoso', 'pakbudi', 'petani@agrilink.id', ?, 'petani', '081234567890', 'Desa Sukamaju, Cisarua, Bogor'),
                ('CV Tani Makmur', 'tanimakmur', 'supplier@agrilink.id', ?, 'supplier', '081298765432', 'Jl. Raya Pertanian No. 45, Bandung'),
                ('Resto Segar Nusantara', 'restosegar', 'buyer@agrilink.id', ?, 'buyer', '081311223344', 'Jl. Boulevard Utama No. 88, Jakarta Selatan'),
                ('Admin AgriLink', 'admin', 'admin@agrilink.id', ?, 'admin', '081100001111', 'Gedung AgriLink HQ, Jakarta Pusat')
            ");

            $insert->execute([$petaniPass, $supplierPass, $buyerPass, $adminPass]);
        }
    }
}
