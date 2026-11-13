<?php

namespace App\Controllers;

use App\Models\EstimateModel;
use App\Models\EstimateItemModel;
use App\Models\FarmPlanModel;
use App\Models\ProductModel;

class Estimasi extends BaseController
{
    protected EstimateModel $estimateModel;
    protected EstimateItemModel $itemModel;
    protected FarmPlanModel $planModel;
    protected ProductModel $productModel;

    public function __construct()
    {
        $this->estimateModel = new EstimateModel();
        $this->itemModel     = new EstimateItemModel();
        $this->planModel     = new FarmPlanModel();
        $this->productModel  = new ProductModel();
    }

    public function index()
    {
        $userId = (int) session()->get('user_id');

        $estimates = $this->estimateModel->getEstimatesWithDetails($userId);
        $plans     = $this->planModel->getPlansWithFarm($userId);
        $products  = $this->productModel->where('status', 'active')->findAll();

        // Ambil item untuk setiap estimasi
        foreach ($estimates as &$est) {
            $est['items'] = $this->itemModel->where('estimate_id', $est['id'])->findAll();
        }
        unset($est);

        return view('estimasi/index', [
            'pageTitle' => 'Estimasi Kebutuhan & Modal — AgriLink',
            'estimates' => $estimates,
            'plans'     => $plans,
            'products'  => $products,
        ]);
    }

    public function simpan()
    {
        $userId     = (int) session()->get('user_id');
        $farmPlanId = (int) $this->request->getPost('farm_plan_id');
        $notes      = trim((string) $this->request->getPost('notes'));

        $itemsName     = (array) $this->request->getPost('item_name');
        $itemsCategory = (array) $this->request->getPost('item_category');
        $itemsQty      = (array) $this->request->getPost('item_quantity');
        $itemsUnit     = (array) $this->request->getPost('item_unit');
        $itemsPrice    = (array) $this->request->getPost('item_price');
        $itemsProduct  = (array) $this->request->getPost('item_product_id');

        if (empty($farmPlanId) || empty($itemsName)) {
            return redirect()->back()->withInput()->with('error', 'Pilih rencana tanam dan tambahkan minimal satu item kebutuhan.');
        }

        // Hitung total dan validasi subtotal (PRD Bab 6.3)
        $totalCost = 0.0;
        $itemsToInsert = [];

        for ($i = 0; $i < count($itemsName); $i++) {
            $name  = trim((string) ($itemsName[$i] ?? ''));
            $qty   = (float) ($itemsQty[$i] ?? 0);
            $price = (float) ($itemsPrice[$i] ?? 0);
            $unit  = trim((string) ($itemsUnit[$i] ?? 'kg')) ?: 'kg';
            $cat   = trim((string) ($itemsCategory[$i] ?? 'Benih')) ?: 'Benih';
            $prodId= !empty($itemsProduct[$i]) ? (int)$itemsProduct[$i] : null;

            if (empty($name) || $qty <= 0 || $price < 0) {
                continue;
            }

            $subtotal = $qty * $price;
            $totalCost += $subtotal;

            $itemsToInsert[] = [
                'product_id'           => $prodId,
                'item_name'            => $name,
                'category'             => $cat,
                'quantity'             => $qty,
                'unit'                 => $unit,
                'estimated_unit_price' => $price,
                'subtotal'             => $subtotal,
            ];
        }

        if (empty($itemsToInsert)) {
            return redirect()->back()->withInput()->with('error', 'Daftar item estimasi tidak valid atau kuantitas/harga tidak boleh nol/negatif.');
        }

        $estimateId = $this->estimateModel->insert([
            'farm_plan_id'         => $farmPlanId,
            'created_by'           => $userId,
            'total_estimated_cost' => $totalCost,
            'status'               => 'final',
            'notes'                => $notes,
        ]);

        foreach ($itemsToInsert as &$item) {
            $item['estimate_id'] = $estimateId;
        }
        $this->itemModel->insertBatch($itemsToInsert);

        return redirect()->to(base_url('estimasi'))->with('success', 'Estimasi kebutuhan dan modal berhasil disimpan (Total: Rp ' . number_format($totalCost, 0, ',', '.') . ').');
    }
}

