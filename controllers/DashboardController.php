<?php
/**
 * Dashboard Controller
 * Menampilkan dashboard terintegrasi sesuai peran pengguna yang login
 * AgriLink PHP Native MVC
 */

require_once ROOT_PATH . '/core/Controller.php';

class DashboardController extends Controller {
    private User $userModel;

    public function __construct() {
        $this->userModel = $this->model('User');
    }

    /**
     * Dashboard Utama (Halaman setelah login berhasil)
     */
    public function index(): void {
        // Wajibkan login
        $this->requireAuth();

        $userId = (int) $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);

        if (!$user) {
            $this->setFlash('error', 'Akun tidak ditemukan. Silakan login kembali.', 'danger');
            $this->redirect('index.php?c=auth&a=login');
        }

        // Sinkronisasi data sesi terkini
        $_SESSION['user_name'] = $user['nama_lengkap'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user'] = $user;

        // Render view dashboard
        $this->view('dashboard/index', [
            'pageTitle' => 'Dashboard ' . ucfirst($user['role']) . ' — AgriLink',
            'user'      => $user,
            'role'      => $user['role']
        ]);
    }
}