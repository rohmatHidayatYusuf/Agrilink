<?php
/**
 * Model Petani
 * AgriLink PHP Native MVC
 */

require_once ROOT_PATH . '/core/Model.php';

class Petani extends Model {
    protected string $table = 'users';

    /**
     * Dapatkan semua data petani aktif
     */
    public function getAllPetani(): array {
        return $this->fetchAll(
            "SELECT `id`, `nama_lengkap`, `username`, `email`, `telepon`, `alamat`, `created_at` 
             FROM `{$this->table}` 
             WHERE `role` = 'petani' AND `status` = 'active' 
             ORDER BY `nama_lengkap` ASC"
        );
    }
}