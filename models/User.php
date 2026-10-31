<?php
/**
 * Model User
 * AgriLink PHP Native MVC
 */

require_once ROOT_PATH . '/core/Model.php';

class User extends Model {
    protected string $table = 'users';

    /**
     * Cari user berdasarkan Email
     */
    public function findByEmail(string $email): ?array {
        return $this->fetchOne(
            "SELECT * FROM `{$this->table}` WHERE `email` = ? LIMIT 1",
            [$email]
        );
    }

    /**
     * Cari user berdasarkan Username
     */
    public function findByUsername(string $username): ?array {
        return $this->fetchOne(
            "SELECT * FROM `{$this->table}` WHERE `username` = ? LIMIT 1",
            [$username]
        );
    }

    /**
     * Cari user berdasarkan Email ATAU Username (untuk fleksibilitas input login)
     */
    public function findByEmailOrUsername(string $identifier): ?array {
        return $this->fetchOne(
            "SELECT * FROM `{$this->table}` WHERE `email` = ? OR `username` = ? LIMIT 1",
            [$identifier, $identifier]
        );
    }

    /**
     * Cari user berdasarkan ID
     */
    public function findById(int $id): ?array {
        return $this->fetchOne(
            "SELECT `id`, `nama_lengkap`, `username`, `email`, `role`, `telepon`, `alamat`, `foto_profil`, `status`, `created_at` 
             FROM `{$this->table}` WHERE `id` = ? LIMIT 1",
            [$id]
        );
    }

    /**
     * Cari satu user berdasarkan Role (untuk demo login persona)
     */
    public function findFirstByRole(string $role): ?array {
        return $this->fetchOne(
            "SELECT * FROM `{$this->table}` WHERE `role` = ? ORDER BY `id` ASC LIMIT 1",
            [$role]
        );
    }

    /**
     * Cek apakah email sudah terdaftar
     */
    public function isEmailTaken(string $email, ?int $excludeId = null): bool {
        $sql = "SELECT COUNT(*) FROM `{$this->table}` WHERE `email` = ?";
        $params = [$email];

        if ($excludeId !== null) {
            $sql .= " AND `id` != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->query($sql, $params);
        return ((int) $stmt->fetchColumn()) > 0;
    }

    /**
     * Cek apakah username sudah terdaftar
     */
    public function isUsernameTaken(string $username, ?int $excludeId = null): bool {
        $sql = "SELECT COUNT(*) FROM `{$this->table}` WHERE `username` = ?";
        $params = [$username];

        if ($excludeId !== null) {
            $sql .= " AND `id` != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->query($sql, $params);
        return ((int) $stmt->fetchColumn()) > 0;
    }

    /**
     * Simpan user baru (Registrasi)
     */
    public function create(array $data): int|false {
        $sql = "
            INSERT INTO `{$this->table}` 
            (`nama_lengkap`, `username`, `email`, `password`, `role`, `telepon`, `alamat`)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";

        // Pastikan password di-hash secara aman menggunakan BCRYPT
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        $params = [
            trim($data['nama_lengkap']),
            strtolower(trim($data['username'])),
            strtolower(trim($data['email'])),
            $hashedPassword,
            $data['role'] ?? 'petani',
            $data['telepon'] ?? null,
            $data['alamat'] ?? null
        ];

        try {
            $this->query($sql, $params);
            return (int) $this->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error User::create: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifikasi kredensial login (Email/Username + Password)
     */
    public function authenticate(string $identifier, string $password): ?array {
        $user = $this->findByEmailOrUsername($identifier);

        if (!$user) {
            return null;
        }

        // Cek status akun
        if (isset($user['status']) && $user['status'] !== 'active') {
            return ['status_error' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi admin.'];
        }

        // Verifikasi password hash
        if (password_verify($password, $user['password'])) {
            // Hapus field password sebelum dikembalikan demi keamanan
            unset($user['password']);
            return $user;
        }

        return null;
    }
}
