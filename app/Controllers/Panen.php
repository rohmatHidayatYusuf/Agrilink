<?php

namespace App\Controllers;

use App\Models\HarvestRecordModel;
use App\Models\HarvestListingModel;
use App\Models\FarmPlanModel;

class Panen extends BaseController
{
    protected HarvestRecordModel $harvestModel;
    protected HarvestListingModel $listingModel;
    protected FarmPlanModel $planModel;

    public function __construct()
    {
        $this->harvestModel = new HarvestRecordModel();
        $this->listingModel = new HarvestListingModel();
        $this->planModel    = new FarmPlanModel();
    }

    public function index()
    {
        $userId = (int) session()->get('user_id');

        $records = $this->harvestModel->getRecordsWithPlan($userId);
        $plans   = $this->planModel->getPlansWithFarm($userId);

        $totalHarvestQty = array_reduce($records, fn($acc, $r) => $acc + (float)$r['quantity'], 0.0);
        $totalSoldQty    = array_reduce($records, fn($acc, $r) => $acc + (float)$r['quantity_sold'], 0.0);
        $totalReadyQty   = max(0.0, $totalHarvestQty - $totalSoldQty);

        return view('panen/index', [
            'pageTitle'       => 'Pencatatan Hasil Panen — AgriLink',
            'records'         => $records,
            'plans'           => $plans,
            'totalHarvestQty' => $totalHarvestQty,
            'totalSoldQty'    => $totalSoldQty,
            'totalReadyQty'   => $totalReadyQty,
        ]);
    }

    public function simpan()
    {
        $userId     = (int) session()->get('user_id');
        $farmPlanId = (int) $this->request->getPost('farm_plan_id');
        $date       = (string) $this->request->getPost('harvest_date');
        $qty        = (float) $this->request->getPost('quantity');
        $unit       = trim((string) $this->request->getPost('unit')) ?: 'kg';
        $quality    = trim((string) $this->request->getPost('quality_notes'));
        $location   = trim((string) $this->request->getPost('storage_location'));

        if ($farmPlanId <= 0 || empty($date) || $qty <= 0) {
            return redirect()->back()->withInput()->with('error', 'Rencana tanam, tanggal panen, dan kuantitas hasil panen (lebih dari 0) wajib diisi.');
        }

        $plan = $this->planModel->where('id', $farmPlanId)->where('created_by', $userId)->first();
        if (!$plan) {
            return redirect()->back()->with('error', 'Rencana tanam yang dipilih tidak valid.');
        }

        $this->harvestModel->insert([
            'farm_plan_id'     => $farmPlanId,
            'farmer_id'        => $userId,
            'commodity'        => $plan['commodity'],
            'harvest_date'     => $date,
            'quantity'         => $qty,
            'quantity_sold'    => 0.00,
            'unit'             => $unit,
            'quality_notes'    => $quality ?: 'Kualitas Standar Panen',
            'storage_location' => $location ?: 'Gudang Petani',
            'status'           => 'stored',
        ]);

        // Perbarui status rencana tanam menjadi 'harvested'
        $this->planModel->update($farmPlanId, ['status' => 'harvested']);

        return redirect()->to(base_url('panen'))->with('success', "Catatan panen <strong>" . esc($plan['commodity']) . "</strong> ({$qty} {$unit}) berhasil disimpan.");
    }

    /**
     * Terbitkan hasil panen fisik menjadi penawaran di Pasar Hasil Tani (PRD Bab 6.6 SALE-01)
     */
    public function terbitkanListing()
    {
        $userId          = (int) session()->get('user_id');
        $harvestRecordId = (int) $this->request->getPost('harvest_record_id');
        $title           = trim((string) $this->request->getPost('title'));
        $desc            = trim((string) $this->request->getPost('description'));
        $price           = (float) $this->request->getPost('price_per_unit');
        $sellQty         = (float) $this->request->getPost('quantity_available');
        $minOrder        = (float) $this->request->getPost('min_order') ?: 1.0;

        if ($harvestRecordId <= 0 || empty($title) || $price <= 0 || $sellQty <= 0) {
            return redirect()->back()->withInput()->with('error', 'Semua formulir penerbitan ke pasar wajib diisi dengan benar.');
        }

        $record = $this->harvestModel->where('id', $harvestRecordId)->where('farmer_id', $userId)->first();
        if (!$record) {
            return redirect()->back()->with('error', 'Catatan panen tidak ditemukan.');
        }

        // Validasi: jumlah yang dijual tidak boleh melebihi sisa hasil panen fisik (PRD Bab 6.5 HAR-07)
        $availablePhysicalQty = (float) $record['quantity'] - (float) $record['quantity_sold'];

        if ($sellQty > $availablePhysicalQty + 0.001) {
            return redirect()->back()->withInput()->with('error', "Kuantitas yang ditawarkan ({$sellQty} {$record['unit']}) melebihi sisa fisik hasil panen yang tersedia ({$availablePhysicalQty} {$record['unit']}).");
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Buat listing pasar hasil tani
        $this->listingModel->insert([
            'harvest_record_id'  => $harvestRecordId,
            'seller_id'          => $userId,
            'title'              => $title,
            'description'        => $desc,
            'price_per_unit'     => $price,
            'quantity_available' => $sellQty,
            'unit'               => $record['unit'],
            'min_order'          => $minOrder,
            'status'             => 'active',
        ]);

        // 2. Alokasikan stok panen (tambah quantity_sold)
        $newSold = (float) $record['quantity_sold'] + $sellQty;
        $this->harvestModel->update($harvestRecordId, [
            'quantity_sold' => $newSold,
            'status'        => 'listed',
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menerbitkan hasil panen ke pasar.');
        }

        return redirect()->to(base_url('pasar'))->with('success', "Hasil panen <strong>" . esc($title) . "</strong> berhasil diterbitkan ke Pasar Hasil Tani!");
    }
}

