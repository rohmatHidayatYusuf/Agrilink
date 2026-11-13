<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (!$session->has('user_id')) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $userRole = strtolower($session->get('user_role') ?? '');

        if (!empty($arguments)) {
            $allowedRoles = array_map('strtolower', $arguments);
            if (!in_array($userRole, $allowedRoles, true)) {
                return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut (' . strtoupper($userRole) . ').');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}

