<?php
/**
 * Router & Application Runner
 * AgriLink PHP Native MVC
 */

class App {
    protected string $controller = 'AuthController';
    protected string $action = 'login';
    protected array $params = [];

    public function __construct() {
        $this->parseRoute();
    }

    /**
     * Parsing rute dari parameter query (?c=controller&a=action atau ?page=...)
     */
    private function parseRoute(): void {
        // Cek parameter ?page=... (shortcut praktis)
        if (isset($_GET['page'])) {
            $page = strtolower(trim($_GET['page']));
            switch ($page) {
                case 'login':
                    $this->controller = 'AuthController';
                    $this->action = 'login';
                    return;
                case 'register':
                    $this->controller = 'AuthController';
                    $this->action = 'register';
                    return;
                case 'logout':
                    $this->controller = 'AuthController';
                    $this->action = 'logout';
                    return;
                case 'dashboard':
                    $this->controller = 'DashboardController';
                    $this->action = 'index';
                    return;
            }
        }

        // Cek jika ada request ?role=... (kompatibilitas demo login persona dari template lama)
        if (isset($_GET['role']) && !isset($_GET['c'])) {
            $this->controller = 'AuthController';
            $this->action = 'demoLogin';
            $this->params = [strtolower(trim($_GET['role']))];
            return;
        }

        // Parameter eksplisit: c=controller & a=action
        $c = $_GET['c'] ?? $_GET['controller'] ?? null;
        $a = $_GET['a'] ?? $_GET['action'] ?? null;

        if ($c) {
            $controllerName = ucfirst(strtolower($c)) . 'Controller';
            if (file_exists(ROOT_PATH . '/controllers/' . $controllerName . '.php')) {
                $this->controller = $controllerName;
            }
        } else {
            // Default: jika sudah login langsung ke Dashboard, jika belum ke Login
            if (!empty($_SESSION['user_id'])) {
                $this->controller = 'DashboardController';
                $this->action = 'index';
            } else {
                $this->controller = 'AuthController';
                $this->action = 'login';
            }
        }

        if ($a) {
            $this->action = $a;
        }
    }

    /**
     * Jalankan controller dan action
     */
    public function run(): void {
        $controllerFile = ROOT_PATH . '/controllers/' . $this->controller . '.php';

        if (!file_exists($controllerFile)) {
            http_response_code(404);
            die("Controller '{$this->controller}' tidak ditemukan.");
        }

        require_once $controllerFile;

        $controllerInstance = new $this->controller();

        if (!method_exists($controllerInstance, $this->action)) {
            http_response_code(404);
            die("Action '{$this->action}' tidak ditemukan pada '{$this->controller}'.");
        }

        // Panggil method dengan parameter
        call_user_func_array([$controllerInstance, $this->action], $this->params);
    }
}
