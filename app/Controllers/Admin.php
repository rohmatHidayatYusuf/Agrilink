<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProductModel;
use App\Models\HarvestListingModel;
use App\Models\AdminAuditLogModel;

class Admin extends BaseController
{
    protected UserModel $userModel;
    protected ProductModel $productModel;
    protected HarvestListingModel $listingModel;
    protected AdminAuditLogModel $auditModel;

    public function __construct()
    {
        $this->userModel    = new UserModel();
        $this->productModel = new ProductModel();
        $this->listingModel = new HarvestListingModel();
        $this->auditModel   = new AdminAuditLogModel();
    }

    /**
     * Manajemen Pengguna Sistem (PRD Bab 6.7 ADM-01 & ADM-02)
     */
    public function users()
    {
        $role   = (string) $this->request->getGet('role');
        $search = trim((string) $this->request->getGet('cari'));

        $builder = $this->userModel;
        if ($role && $role !== 'all') {
            $builder = $builder->where('role', $role);
        }
        if ($search) {
            $builder = $builder->groupStart()
                               ->like('nama_lengkap', $search)
                               ->orLike('email', $search)
                               ->orLike('username', $search)
                               ->groupEnd();
        }

        $users = $builder->orderBy('id', 'ASC')->findAll();

        return view('admin/users', [
            'pageTitle' => 'Manajemen Pengguna — Admin AgriLink',
            'users'     => $users,
            'role'      => $role,
            'cari'      => $search,
        ]);
    }

    /**
     * Nonaktifkan / Aktifkan akun pengguna
     */
    public function toggleUserStatus(int $userId)
    {
        $adminId = (int) session()->get('user_id');
        $user    = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }

        if ($user['role'] === 'admin') {
            return redirect()->back()->with('error', 'Akun admin tidak dapat dinonaktifkan.');
        }

        $newStatus = ($user['status'] === 'active') ? 'suspended' : 'active';
        $this->userModel->update($userId, ['status' => $newStatus]);

        // Catat jejak audit (PRD Bab 6.7 ADM-06)
        $this->auditModel->logAction(
            $adminId,
            'TOGGLE_USER_STATUS',
            'users',
            $userId,
            "Mengubah status pengguna {$user['nama_lengkap']} (#{$userId}) dari {$user['status']} menjadi {$newStatus}."
        );

        return redirect()->back()->with('success', "Status akun <strong>" . esc($user['nama_lengkap']) . "</strong> berhasil diubah menjadi <strong>" . strtoupper($newStatus) . "</strong>.");
    }

    /**
     * Moderasi Konten & Listing (PRD Bab 6.7 ADM-03 & ADM-04)
     */
    public function moderasi()
    {
        $products = $this->productModel->select('products.*, users.nama_lengkap as supplier_name')
                                       ->join('users', 'users.id = products.supplier_id')
                                       ->orderBy('products.id', 'DESC')
                                       ->findAll();

        $listings = $this->listingModel->select('harvest_listings.*, users.nama_lengkap as seller_name')
                                       ->join('users', 'users.id = harvest_listings.seller_id')
                                       ->orderBy('harvest_listings.id', 'DESC')
                                       ->findAll();

        return view('admin/moderasi', [
            'pageTitle' => 'Moderasi Konten & Listing — Admin AgriLink',
            'products'  => $products,
            'listings'  => $listings,
        ]);
    }

    /**
     * Nonaktifkan listing yang bermasalah / pulihkan
     */
    public function toggleModerasi(string $type, int $id)
    {
        $adminId = (int) session()->get('user_id');

        if ($type === 'saprotan') {
            $item = $this->productModel->find($id);
            if (!$item) return redirect()->back()->with('error', 'Produk tidak ditemukan.');
            $newStatus = ($item['status'] === 'active') ? 'moderated' : 'active';
            $this->productModel->update($id, ['status' => $newStatus]);
            $action = "MODERASI_SAPROTAN";
        } elseif ($type === 'panen') {
            $item = $this->listingModel->find($id);
            if (!$item) return redirect()->back()->with('error', 'Listing tidak ditemukan.');
            $newStatus = ($item['status'] === 'active') ? 'moderated' : 'active';
            $this->listingModel->update($id, ['status' => $newStatus]);
            $action = "MODERASI_PANEN";
        } else {
            return redirect()->back()->with('error', 'Tipe item tidak valid.');
        }

        $this->auditModel->logAction(
            $adminId,
            $action,
            $type,
            $id,
            "Moderasi item #{$id}: status diubah menjadi {$newStatus}."
        );

        return redirect()->back()->with('success', "Status moderasi item berhasil diperbarui menjadi <strong>" . strtoupper($newStatus) . "</strong>.");
    }

    /**
     * Audit Log Sistem (PRD Bab 6.7 ADM-06)
     */
    public function audit()
    {
        $logs = $this->auditModel->getRecentLogs(100);

        return view('admin/audit', [
            'pageTitle' => 'Audit Log Sistem — Admin AgriLink',
            'logs'      => $logs,
        ]);
    }
}

