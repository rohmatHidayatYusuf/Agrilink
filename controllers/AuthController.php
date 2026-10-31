<?php
/**
 * Auth Controller
 * Menangani alur Autentikasi: Login, Register, Logout, dan Demo Login
 * AgriLink PHP Native MVC
 */

require_once ROOT_PATH . '/core/Controller.php';

class AuthController extends Controller {
    private User $userModel;

    public function __construct() {
        $this->userModel = $this->model('User');
    }

    /**
     * Halaman & Proses Login
     */
    public function login(): void {
        // Jika sudah login, langsung lempar ke dashboard
        $this->requireGuest();

        $errors = [];
        $old = [
            'identifier' => ''
        ];

        // Tangani Request POST Login
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!$this->validateCsrf($csrfToken)) {
                $errors['general'] = 'Sesi tidak valid atau telah kedaluwarsa. Silakan muat ulang halaman.';
            } else {
                $identifier = trim($_POST['identifier'] ?? '');
                $password = $_POST['password'] ?? '';
                $remember = !empty($_POST['remember']);

                $old['identifier'] = $identifier;

                if (empty($identifier)) {
                    $errors['identifier'] = 'Email atau username wajib diisi.';
                }

                if (empty($password)) {
                    $errors['password'] = 'Kata sandi wajib diisi.';
                }

                if (empty($errors)) {
                    $user = $this->userModel->authenticate($identifier, $password);

                    if ($user) {
                        if (isset($user['status_error'])) {
                            $errors['general'] = $user['status_error'];
                        } else {
                            // Login Berhasil!
                            session_regenerate_id(true);

                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['user_name'] = $user['nama_lengkap'];
                            $_SESSION['user_username'] = $user['username'];
                            $_SESSION['user_email'] = $user['email'];
                            $_SESSION['user_role'] = $user['role'];
                            $_SESSION['user'] = $user;

                            $this->setFlash('success', "Selamat datang kembali, <strong>" . htmlspecialchars($user['nama_lengkap']) . "</strong>! Anda masuk sebagai <strong>" . strtoupper($user['role']) . "</strong>.");
                            
                            $this->redirect('index.php?c=dashboard&a=index');
                        }
                    } else {
                        $errors['general'] = 'Email/Username atau kata sandi tidak cocok. Silakan coba lagi.';
                    }
                }
            }
        }

        // Tampilkan View Login
        $this->view('auth/login', [
            'pageTitle' => 'Masuk — AgriLink',
            'errors'    => $errors,
            'old'       => $old,
            'csrfToken' => $this->csrfToken()
        ]);
    }

    /**
     * Halaman & Proses Register
     */
    public function register(): void {
        // Jika sudah login, redirect ke dashboard
        $this->requireGuest();

        $errors = [];
        $old = [
            'nama_lengkap' => '',
            'username'     => '',
            'email'        => '',
            'telepon'      => '',
            'role'         => 'petani',
            'alamat'       => ''
        ];

        // Tangani Request POST Register
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!$this->validateCsrf($csrfToken)) {
                $errors['general'] = 'Sesi tidak valid. Silakan muat ulang halaman.';
            } else {
                $namaLengkap = trim($_POST['nama_lengkap'] ?? '');
                $username    = strtolower(trim($_POST['username'] ?? ''));
                $email       = strtolower(trim($_POST['email'] ?? ''));
                $telepon     = trim($_POST['telepon'] ?? '');
                $role        = strtolower(trim($_POST['role'] ?? 'petani'));
                $alamat      = trim($_POST['alamat'] ?? '');
                $password    = $_POST['password'] ?? '';
                $confirmPass = $_POST['confirm_password'] ?? '';
                $agreeTerms  = !empty($_POST['agree_terms']);

                $old = [
                    'nama_lengkap' => $namaLengkap,
                    'username'     => $username,
                    'email'        => $email,
                    'telepon'      => $telepon,
                    'role'         => $role,
                    'alamat'       => $alamat
                ];

                // 1. Validasi Nama Lengkap
                if (empty($namaLengkap)) {
                    $errors['nama_lengkap'] = 'Nama lengkap wajib diisi.';
                } elseif (strlen($namaLengkap) < 3) {
                    $errors['nama_lengkap'] = 'Nama lengkap minimal 3 karakter.';
                }

                // 2. Validasi Username
                if (empty($username)) {
                    $errors['username'] = 'Username wajib diisi.';
                } elseif (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username)) {
                    $errors['username'] = 'Username hanya boleh huruf, angka, dan underscore (3-30 karakter).';
                } elseif ($this->userModel->isUsernameTaken($username)) {
                    $errors['username'] = 'Username ini sudah digunakan. Silakan pilih username lain.';
                }

                // 3. Validasi Email
                if (empty($email)) {
                    $errors['email'] = 'Email wajib diisi.';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors['email'] = 'Format email tidak valid.';
                } elseif ($this->userModel->isEmailTaken($email)) {
                    $errors['email'] = 'Email ini sudah terdaftar. Silakan gunakan email lain atau masuk.';
                }

                // 4. Validasi Role
                $validRoles = ['petani', 'supplier', 'buyer'];
                if (!in_array($role, $validRoles, true)) {
                    $errors['role'] = 'Peran yang dipilih tidak valid.';
                }

                // 5. Validasi Nomor Telepon
                if (!empty($telepon) && !preg_match('/^[0-9+\-\s]{8,20}$/', $telepon)) {
                    $errors['telepon'] = 'Nomor telepon tidak valid (hanya angka/tanda +).';
                }

                // 6. Validasi Password
                if (empty($password)) {
                    $errors['password'] = 'Kata sandi wajib diisi.';
                } elseif (strlen($password) < 6) {
                    $errors['password'] = 'Kata sandi minimal 6 karakter.';
                }

                // 7. Validasi Konfirmasi Password
                if ($password !== $confirmPass) {
                    $errors['confirm_password'] = 'Konfirmasi kata sandi tidak cocok.';
                }

                // 8. Validasi Persetujuan Syarat & Ketentuan
                if (!$agreeTerms) {
                    $errors['agree_terms'] = 'Anda harus menyetujui syarat & ketentuan layanan AgriLink.';
                }

                // Simpan ke Database jika tidak ada error
                if (empty($errors)) {
                    $newUserId = $this->userModel->create([
                        'nama_lengkap' => $namaLengkap,
                        'username'     => $username,
                        'email'        => $email,
                        'password'     => $password,
                        'role'         => $role,
                        'telepon'      => $telepon,
                        'alamat'       => $alamat
                    ]);

                    if ($newUserId) {
                        $this->setFlash('success', "Registrasi berhasil! Akun untuk <strong>" . htmlspecialchars($namaLengkap) . "</strong> (" . ucfirst($role) . ") telah dibuat. Silakan login.");
                        $this->redirect('index.php?c=auth&a=login');
                    } else {
                        $errors['general'] = 'Gagal mendaftarkan akun karena kesalahan sistem. Silakan coba lagi.';
                    }
                }
            }
        }

        // Tampilkan View Register
        $this->view('auth/register', [
            'pageTitle' => 'Daftar Akun Baru — AgriLink',
            'errors'    => $errors,
            'old'       => $old,
            'csrfToken' => $this->csrfToken()
        ]);
    }

    /**
     * Demo Persona Fast Login (1-klik langsung masuk sesuai peran demo)
     */
    public function demoLogin(?string $role = null): void {
        if ($role === null && isset($_GET['role'])) {
            $role = strtolower(trim($_GET['role']));
        }

        $validRoles = ['petani', 'supplier', 'buyer', 'admin'];
        if (!in_array($role, $validRoles, true)) {
            $this->setFlash('error', 'Peran demo tidak valid.', 'danger');
            $this->redirect('index.php?c=auth&a=login');
        }

        // Cari user sesuai role
        $user = $this->userModel->findFirstByRole($role);

        if (!$user) {
            $this->setFlash('error', "User demo dengan peran {$role} belum tersedia.", 'danger');
            $this->redirect('index.php?c=auth&a=login');
        }

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nama_lengkap'];
        $_SESSION['user_username'] = $user['username'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user'] = $user;

        $this->setFlash('success', "Berhasil masuk sebagai Demo <strong>" . htmlspecialchars($user['nama_lengkap']) . "</strong> (Peran: <strong>" . strtoupper($user['role']) . "</strong>).");
        $this->redirect('index.php?c=dashboard&a=index');
    }

    /**
     * Logout
     */
    public function logout(): void {
        // Hapus variabel session
        $_SESSION = [];

        // Hapus cookie session jika ada
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Hancurkan session
        session_destroy();

        // Buat session baru untuk pesan flash
        session_start();
        $this->setFlash('info', 'Anda telah berhasil keluar dari akun AgriLink.');
        $this->redirect('index.php?c=auth&a=login');
    }
}
