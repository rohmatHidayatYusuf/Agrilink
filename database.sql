-- ==============================================================
-- DATABASE SCHEMA: AgriLink (agrilink_db)
-- Sistem Manajemen Pertanian Terintegrasi - PHP Native MVC
-- ==============================================================

CREATE DATABASE IF NOT EXISTS `agrilink_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `agrilink_db`;

-- -------------------------------------------------------------
-- TABEL: users
-- Menyimpan kredensial dan profil pengguna sesuai peran (Role)
-- -------------------------------------------------------------
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

-- -------------------------------------------------------------
-- SEED DATA DEFAULT (Demo Persona)
-- Password default untuk semua akun demo adalah: 'password' atau '<role>123'
-- Hash di bawah dibuat menggunakan password_hash('password', PASSWORD_BCRYPT)
-- -------------------------------------------------------------
INSERT INTO `users` (`nama_lengkap`, `username`, `email`, `password`, `role`, `telepon`, `alamat`)
VALUES 
('Pak Budi Santoso', 'pakbudi', 'petani@agrilink.id', '$2y$10$wE2k9d8F9kG24rAovg1a9uvW7t4U9xI2O63G8e5F19M6f5KzR2k9W', 'petani', '081234567890', 'Desa Sukamaju, Cisarua, Bogor'),
('CV Tani Makmur', 'tanimakmur', 'supplier@agrilink.id', '$2y$10$wE2k9d8F9kG24rAovg1a9uvW7t4U9xI2O63G8e5F19M6f5KzR2k9W', 'supplier', '081298765432', 'Jl. Raya Pertanian No. 45, Bandung'),
('Resto Segar Nusantara', 'restosegar', 'buyer@agrilink.id', '$2y$10$wE2k9d8F9kG24rAovg1a9uvW7t4U9xI2O63G8e5F19M6f5KzR2k9W', 'buyer', '081311223344', 'Jl. Boulevard Utama No. 88, Jakarta Selatan'),
('Admin Pengawas', 'admin', 'admin@agrilink.id', '$2y$10$wE2k9d8F9kG24rAovg1a9uvW7t4U9xI2O63G8e5F19M6f5KzR2k9W', 'admin', '081100001111', 'AgriLink HQ Lt. 4, Jakarta Pusat')
ON DUPLICATE KEY UPDATE `updated_at` = CURRENT_TIMESTAMP;
