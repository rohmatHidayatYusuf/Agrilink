<?php
/**
 * Base Controller
 * AgriLink PHP Native MVC
 */

abstract class Controller {

    /**
     * Memuat file View
     * @param string $viewPath Jalur relatif dari folder views (contoh: 'auth/login')
     * @param array $data Data yang diteruskan ke view
     */
    public function view(string $viewPath, array $data = []): void {
        // Ekstrak data menjadi variabel lokal
        extract($data);

        $viewFile = ROOT_PATH . '/views/' . $viewPath . '.php';

        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View file tidak ditemukan: " . htmlspecialchars($viewFile));
        }
    }

    /**
     * Memuat Model
     * @param string $modelName Nama class model (contoh: 'User')
     */
    public function model(string $modelName): object {
        $modelFile = ROOT_PATH . '/models/' . $modelName . '.php';

        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $modelName();
        }

        die("Model file tidak ditemukan: " . htmlspecialchars($modelFile));
    }

    /**
     * Redirect ke URL tertentu
     */
    public function redirect(string $url): void {
        if (!preg_match('#^https?://#i', $url)) {
            $url = BASE_URL . '/' . ltrim($url, '/');
        }
        header("Location: " . $url);
        exit;
    }

    /**
     * Set Flash message (notifikasi satu kali tampil)
     */
    public function setFlash(string $key, string $message, string $type = 'success'): void {
        $_SESSION['flash'][$key] = [
            'message' => $message,
            'type'    => $type
        ];
    }

    /**
     * Ambil Flash message dan hapus dari sesi
     */
    public function getFlash(string $key): ?array {
        if (isset($_SESSION['flash'][$key])) {
            $flash = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $flash;
        }
        return null;
    }

    /**
     * Periksa keberadaan flash message
     */
    public function hasFlash(string $key): bool {
        return isset($_SESSION['flash'][$key]);
    }

    /**
     * Ambil atau buat CSRF Token untuk proteksi form
     */
    public function csrfToken(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validasi CSRF Token
     */
    public function validateCsrf(?string $token): bool {
        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Periksa apakah pengguna sudah login
     */
    public function isLoggedIn(): bool {
        return !empty($_SESSION['user_id']);
    }

    /**
     * Dapatkan data user yang sedang aktif
     */
    public function currentUser(): ?array {
        if (!$this->isLoggedIn()) {
            return null;
        }
        return $_SESSION['user'] ?? null;
    }

    /**
     * Wajibkan pengguna untuk login sebelum mengakses
     */
    public function requireAuth(): void {
        if (!$this->isLoggedIn()) {
            $this->setFlash('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.', 'danger');
            $this->redirect('index.php?c=auth&a=login');
        }
    }

    /**
     * Wajibkan pengguna belum login (guest only, untuk halaman login/register)
     */
    public function requireGuest(): void {
        if ($this->isLoggedIn()) {
            $role = $_SESSION['user']['role'] ?? 'petani';
            $this->redirect('index.php?c=dashboard&a=index');
        }
    }
}
