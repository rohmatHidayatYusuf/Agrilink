<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SubscriptionModel;

class Auth extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Entry Point Utama (Redirect ke Dashboard jika sudah login, atau Login jika belum)
     */
    public function index()
    {
        if (session()->has('user_id')) {
            return redirect()->to(base_url('dashboard'));
        }
        return redirect()->to(base_url('login'));
    }

    /**
     * Halaman & Proses Login
     */
    public function login()
    {
        if (session()->has('user_id')) {
            return redirect()->to(base_url('dashboard'));
        }

        if ($this->request->is('post')) {
            $identifier = trim((string) $this->request->getPost('identifier'));
            $password   = (string) $this->request->getPost('password');

            if (empty($identifier) || empty($password)) {
                return redirect()->back()->withInput()->with('error', 'Email/Username dan kata sandi wajib diisi.');
            }

            $user = $this->userModel->authenticate($identifier, $password);

            if (!$user) {
                return redirect()->back()->withInput()->with('error', 'Email/Username atau kata sandi tidak cocok. Silakan coba lagi.');
            }

            if (isset($user['status_error'])) {
                return redirect()->back()->withInput()->with('error', $user['status_error']);
            }

            // Set Session
            session()->regenerate();
            session()->set([
                'user_id'       => (int) $user['id'],
                'user_name'     => $user['nama_lengkap'],
                'user_username' => $user['username'],
                'user_email'    => $user['email'],
                'user_role'     => strtolower($user['role']),
                'user_phone'    => $user['telepon'],
                'user_address'  => $user['alamat'],
            ]);

            return redirect()->to(base_url('dashboard'))->with('success', 'Selamat datang kembali, <strong>' . esc($user['nama_lengkap']) . '</strong>! Anda masuk sebagai <strong>' . strtoupper($user['role']) . '</strong>.');
        }

        return view('auth/login', [
            'pageTitle' => 'Masuk — AgriLink',
        ]);
    }

    /**
     * Halaman & Proses Registrasi Akun Baru
     */
    public function register()
    {
        if (session()->has('user_id')) {
            return redirect()->to(base_url('dashboard'));
        }

        if ($this->request->is('post')) {
            $rules = [
                'nama_lengkap' => 'required|min_length[3]|max_length[100]',
                'username'     => 'required|alpha_dash|min_length[3]|max_length[50]|is_unique[users.username]',
                'email'        => 'required|valid_email|is_unique[users.email]',
                'password'     => 'required|min_length[6]',
                'role'         => 'required|in_list[petani,supplier,buyer]',
                'telepon'      => 'permit_empty|min_length[8]|max_length[20]',
                'alamat'       => 'permit_empty|max_length[500]',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $userData = [
                'nama_lengkap' => trim((string) $this->request->getPost('nama_lengkap')),
                'username'     => strtolower(trim((string) $this->request->getPost('username'))),
                'email'        => strtolower(trim((string) $this->request->getPost('email'))),
                'password'     => password_hash((string) $this->request->getPost('password'), PASSWORD_BCRYPT),
                'role'         => strtolower(trim((string) $this->request->getPost('role'))),
                'telepon'      => trim((string) $this->request->getPost('telepon')),
                'alamat'       => trim((string) $this->request->getPost('alamat')),
                'status'       => 'active',
            ];

            $newUserId = $this->userModel->insert($userData);

            if (!$newUserId) {
                return redirect()->back()->withInput()->with('error', 'Gagal mendaftarkan akun. Silakan periksa kembali data Anda.');
            }

            // Daftarkan langganan MVP otomatis
            $subModel = new SubscriptionModel();
            $subModel->insert([
                'user_id'    => $newUserId,
                'plan_id'    => 1, // Paket MVP Terintegrasi
                'status'     => 'active',
                'start_at'   => date('Y-m-d H:i:s'),
                'end_at'     => date('Y-m-d H:i:s', strtotime('+1 year')),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            // Jika mendaftar sebagai petani dan menginput data lahan awal
            $initialFarmArea = (float) $this->request->getPost('initial_farm_area');
            $initialFarmName = trim((string) $this->request->getPost('initial_farm_name'));
            if ($userData['role'] === 'petani' && $initialFarmArea > 0) {
                $farmModel = new \App\Models\FarmModel();
                $farmModel->insert([
                    'owner_id'   => $newUserId,
                    'name'       => !empty($initialFarmName) ? $initialFarmName : 'Lahan Utama ' . $userData['nama_lengkap'],
                    'location'   => !empty($userData['alamat']) ? $userData['alamat'] : 'Lokasi Terdaftar',
                    'area_size'  => $initialFarmArea,
                    'area_unit'  => 'Ha',
                    'status'     => 'active',
                ]);
            }

            return redirect()->to(base_url('login'))->with('success', 'Pendaftaran akun <strong>' . esc($userData['nama_lengkap']) . '</strong> berhasil! Silakan masuk dengan kredensial Anda.');
        }

        return view('auth/register', [
            'pageTitle' => 'Daftar Akun Baru — AgriLink',
        ]);
    }

    /**
     * Demo Persona Quick Switcher (Untuk testing cepat hak akses PRD)
     */
    public function demoLogin(string $role)
    {
        $role = strtolower(trim($role));
        $user = $this->userModel->getDemoUserByRole($role);

        if (!$user) {
            return redirect()->to(base_url('login'))->with('error', "Akun demo untuk peran {$role} tidak ditemukan.");
        }

        session()->regenerate();
        session()->set([
            'user_id'       => (int) $user['id'],
            'user_name'     => $user['nama_lengkap'],
            'user_username' => $user['username'],
            'user_email'    => $user['email'],
            'user_role'     => strtolower($user['role']),
            'user_phone'    => $user['telepon'],
            'user_address'  => $user['alamat'],
        ]);

        return redirect()->to(base_url('dashboard'))->with('success', "Beralih peran demo: <strong>" . strtoupper($role) . "</strong> (" . esc($user['nama_lengkap']) . ").");
    }

    /**
     * Logout
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'))->with('info', 'Anda telah keluar dari sistem AgriLink.');
    }
}

