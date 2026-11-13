<?php

namespace App\Controllers;

use App\Models\FarmModel;
use App\Models\FarmPlanModel;
use App\Models\EstimateModel;
use App\Models\HarvestRecordModel;
use App\Models\HarvestListingModel;
use App\Models\ProductModel;
use App\Models\SupplyOrderModel;
use App\Models\SalesOrderModel;
use App\Models\UserModel;
use App\Models\SubscriptionModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $userId   = (int) session()->get('user_id');
        $userRole = (string) session()->get('user_role');

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            session()->destroy();
            return redirect()->to(base_url('login'))->with('error', 'Sesi tidak valid.');
        }

        $data = [
            'pageTitle' => 'Dashboard ' . ucfirst($userRole) . ' — AgriLink',
            'user'      => $user,
            'role'      => $userRole,
            'stats'     => [],
        ];

        // Dapatkan data langganan
        $subModel = new SubscriptionModel();
        $data['subscription'] = $subModel->getUserSubscription($userId);

        if ($userRole === 'petani') {
            $farmModel     = new FarmModel();
            $planModel     = new FarmPlanModel();
            $harvestModel  = new HarvestRecordModel();
            $listingModel  = new HarvestListingModel();
            $estimateModel = new EstimateModel();
            $salesOrderModel = new SalesOrderModel();

            $farms = $farmModel->where('owner_id', $userId)->where('status', 'active')->findAll();
            $totalArea = array_reduce($farms, fn($acc, $f) => $acc + (float)$f['area_size'], 0.0);

            $data['stats'] = [
                'total_farms'     => count($farms),
                'total_area'      => $totalArea,
                'active_plans'    => $planModel->where('created_by', $userId)->where('status', 'active')->countAllResults(),
                'harvest_records' => $harvestModel->where('farmer_id', $userId)->countAllResults(),
                'active_listings' => $listingModel->where('seller_id', $userId)->where('status', 'active')->countAllResults(),
                'pending_orders'  => $salesOrderModel->where('seller_id', $userId)->whereIn('status', ['menunggu_konfirmasi', 'dikonfirmasi'])->countAllResults(),
                'total_estimates' => $estimateModel->where('created_by', $userId)->countAllResults(),
            ];

            $data['recent_plans']    = $planModel->getPlansWithFarm($userId);
            $data['recent_harvests'] = $harvestModel->getRecordsWithPlan($userId);
        } elseif ($userRole === 'supplier') {
            $productModel     = new ProductModel();
            $supplyOrderModel = new SupplyOrderModel();

            $products = $productModel->where('supplier_id', $userId)->findAll();
            $data['stats'] = [
                'total_products' => count($products),
                'active_products'=> count(array_filter($products, fn($p) => $p['status'] === 'active')),
                'pending_orders' => $supplyOrderModel->where('supplier_id', $userId)->where('status', 'menunggu_konfirmasi')->countAllResults(),
                'total_orders'   => $supplyOrderModel->where('supplier_id', $userId)->countAllResults(),
            ];
            $data['recent_orders'] = $supplyOrderModel->getOrdersBySupplier($userId);
        } elseif ($userRole === 'buyer') {
            $listingModel    = new HarvestListingModel();
            $salesOrderModel = new SalesOrderModel();

            $data['stats'] = [
                'available_commodities' => $listingModel->where('status', 'active')->countAllResults(),
                'active_orders'         => $salesOrderModel->where('buyer_id', $userId)->whereNotIn('status', ['selesai', 'dibatalkan', 'ditolak'])->countAllResults(),
                'completed_orders'      => $salesOrderModel->where('buyer_id', $userId)->where('status', 'selesai')->countAllResults(),
            ];
            $data['recent_orders'] = $salesOrderModel->getOrdersByBuyer($userId);
        } elseif ($userRole === 'admin') {
            $productModel    = new ProductModel();
            $listingModel    = new HarvestListingModel();
            $supplyOrderModel= new SupplyOrderModel();
            $salesOrderModel = new SalesOrderModel();
            $auditModel      = new \App\Models\AdminAuditLogModel();

            $data['stats'] = [
                'total_users'     => $userModel->countAllResults(),
                'total_farmers'   => $userModel->where('role', 'petani')->countAllResults(),
                'total_suppliers' => $userModel->where('role', 'supplier')->countAllResults(),
                'total_buyers'    => $userModel->where('role', 'buyer')->countAllResults(),
                'total_products'  => $productModel->countAllResults(),
                'total_listings'  => $listingModel->countAllResults(),
                'total_orders'    => $supplyOrderModel->countAllResults() + $salesOrderModel->countAllResults(),
            ];
            $data['recent_audits'] = $auditModel->getRecentLogs(10);
        }

        return view('dashboard/index', $data);
    }
}

