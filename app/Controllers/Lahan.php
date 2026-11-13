<?php

namespace App\Controllers;

use App\Models\FarmModel;
use App\Models\FarmPlanModel;

class Lahan extends BaseController
{
    protected FarmModel $farmModel;
    protected FarmPlanModel $planModel;

    public function __construct()
    {
        $this->farmModel = new FarmModel();
        $this->planModel = new FarmPlanModel();
    }

    public function index()
    {
        $userId = (int) session()->get('user_id');

        $farms = $this->farmModel->getFarmsByOwner($userId);
        $plans = $this->planModel->getPlansWithFarm($userId);

        $totalArea = array_reduce($farms, fn($acc, $f) => $acc + (float)$f['area_size'], 0.0);
        $totalUsed = array_reduce($farms, fn($acc, $f) => $acc + (float)$f['total_used_area'], 0.0);
        $totalRemaining = max(0.0, $totalArea - $totalUsed);

        return view('lahan/index', [
            'pageTitle'      => 'Perencanaan Lahan — AgriLink',
            'farms'          => $farms,
            'plans'          => $plans,
            'totalArea'      => $totalArea,
            'totalUsed'      => $totalUsed,
            'totalRemaining' => $totalRemaining,
        ]);
    }

    public function simpan()
    {
        $userId = (int) session()->get('user_id');

        $name     = trim((string) $this->request->getPost('name'));
        $location = trim((string) $this->request->getPost('location'));
        $areaSize = (float) $this->request->getPost('area_size');
        $areaUnit = trim((string) $this->request->getPost('area_unit')) ?: 'Ha';

        if (empty($name) || empty($location) || $areaSize <= 0) {
            return redirect()->back()->withInput()->with('error', 'Nama lahan, lokasi, dan luas lahan (lebih dari 0) wajib diisi.');
        }

        $this->farmModel->insert([
            'owner_id'  => $userId,
            'name'      => $name,
            'location'  => $location,
            'area_size' => $areaSize,
            'area_unit' => $areaUnit,
            'status'    => 'active',
        ]);

        return redirect()->to(base_url('lahan'))->with('success', "Profil lahan <strong>" . esc($name) . "</strong> berhasil ditambahkan.");
    }

    public function simpanRencana()
    {
        $userId = (int) session()->get('user_id');

        $farmId      = (int) $this->request->getPost('farm_id');
        $commodity   = trim((string) $this->request->getPost('commodity'));
        $plannedArea = (float) $this->request->getPost('planned_area');
        $startDate   = (string) $this->request->getPost('start_date');
        $harvestDate = (string) $this->request->getPost('expected_harvest_date');

        if (empty($commodity) || $plannedArea <= 0 || empty($startDate) || empty($harvestDate)) {
            return redirect()->back()->withInput()->with('error', 'Semua kolom rencana tanam wajib diisi dengan benar.');
        }

        // Verifikasi kepemilikan lahan
        $farm = $this->farmModel->where('id', $farmId)->where('owner_id', $userId)->first();
        if (!$farm) {
            return redirect()->back()->with('error', 'Lahan yang dipilih tidak valid atau bukan milik Anda.');
        }

        // Validasi luas tanam tidak melebihi sisa luas lahan (PRD Bab 6.2 Acceptance Criteria)
        $currentUsedArea = $this->planModel->where('farm_id', $farmId)
                                           ->where('status', 'active')
                                           ->selectSum('planned_area')
                                           ->first()['planned_area'] ?? 0.0;

        $remainingArea = (float) $farm['area_size'] - (float) $currentUsedArea;

        if ($plannedArea > $remainingArea + 0.001) {
            return redirect()->back()->withInput()->with('error', "Luas tanam ({$plannedArea} Ha) melebihi sisa kapasitas lahan yang tersedia ({$remainingArea} Ha).");
        }

        $this->planModel->insert([
            'farm_id'               => $farmId,
            'created_by'            => $userId,
            'commodity'             => $commodity,
            'planned_area'          => $plannedArea,
            'start_date'            => $startDate,
            'expected_harvest_date' => $harvestDate,
            'status'                => 'active',
        ]);

        return redirect()->to(base_url('lahan'))->with('success', "Rencana penanaman <strong>" . esc($commodity) . "</strong> berhasil disimpan.");
    }

    public function arsipLahan(int $id)
    {
        $userId = (int) session()->get('user_id');
        $farm = $this->farmModel->where('id', $id)->where('owner_id', $userId)->first();

        if ($farm) {
            $this->farmModel->update($id, ['status' => 'archived']);
            return redirect()->to(base_url('lahan'))->with('info', "Lahan " . esc($farm['name']) . " telah diarsipkan.");
        }

        return redirect()->to(base_url('lahan'))->with('error', 'Lahan tidak ditemukan.');
    }
}

