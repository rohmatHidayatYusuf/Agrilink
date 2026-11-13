<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_lengkap',
        'username',
        'email',
        'password',
        'role',
        'telepon',
        'alamat',
        'foto_profil',
        'status',
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'nama_lengkap' => 'required|min_length[3]|max_length[100]',
        'username'     => 'required|alpha_numeric_space|min_length[3]|max_length[50]|is_unique[users.username,id,{id}]',
        'email'        => 'required|valid_email|is_unique[users.email,id,{id}]',
        'role'         => 'required|in_list[petani,supplier,buyer,admin]',
    ];
    protected $validationMessages   = [
        'email' => [
            'is_unique' => 'Alamat email ini sudah terdaftar. Silakan gunakan email lain atau masuk ke akun Anda.',
        ],
        'username' => [
            'is_unique' => 'Username ini sudah digunakan.',
        ],
    ];
    protected $skipValidation       = false;

    /**
     * Otentikasi Pengguna (Login via Email ATAU Username)
     */
    public function authenticate(string $identifier, string $password): ?array
    {
        $user = $this->where('email', $identifier)
                     ->orWhere('username', $identifier)
                     ->first();

        if (!$user) {
            return null;
        }

        if (!password_verify($password, $user['password'])) {
            return null;
        }

        if ($user['status'] !== 'active') {
            return [
                'status_error' => 'Akun Anda sedang dinonaktifkan (' . $user['status'] . '). Silakan hubungi admin AgriLink.',
            ];
        }

        return $user;
    }

    /**
     * Dapatkan satu akun demo berdasarkan role
     */
    public function getDemoUserByRole(string $role): ?array
    {
        return $this->where('role', $role)->where('status', 'active')->first();
    }
}
