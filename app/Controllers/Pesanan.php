<?php

namespace App\Controllers;

use App\Models\SupplyOrderModel;
use App\Models\SupplyOrderItemModel;
use App\Models\SalesOrderModel;
use App\Models\SalesOrderItemModel;
use App\Models\OrderStatusHistoryModel;

class Pesanan extends BaseController
{
    protected SupplyOrderModel $supplyOrderModel;
    protected SupplyOrderItemModel $supplyItemModel;
    protected SalesOrderModel $salesOrderModel;
    protected SalesOrderItemModel $salesItemModel;
    protected OrderStatusHistoryModel $statusHistoryModel;

    public function __construct()
    {
        $this->supplyOrderModel   = new SupplyOrderModel();
        $this->supplyItemModel    = new SupplyOrderItemModel();
        $this->salesOrderModel    = new SalesOrderModel();
        $this->salesItemModel     = new SalesOrderItemModel();
        $this->statusHistoryModel = new OrderStatusHistoryModel();
    }

    /**
     * Dashboard Pesanan & Transaksi Petani (Memantau Penjualan Hasil Tani & Pembelian Saprotan)
     */
    public function index()
    {
        $userId = (int) session()->get('user_id');

        // Pesanan Penjualan Hasil Tani (Petani sebagai Seller)
        $incomingSalesOrders = $this->salesOrderModel->getOrdersBySeller($userId);
        foreach ($incomingSalesOrders as &$order) {
            $order['items']   = $this->salesItemModel->where('order_id', $order['id'])->findAll();
            $order['history'] = $this->statusHistoryModel->getHistory('sales', $order['id']);
        }
        unset($order);

        // Pesanan Pengadaan Saprotan (Petani sebagai Buyer)
        $outgoingSupplyOrders = $this->supplyOrderModel->getOrdersByBuyer($userId);
        foreach ($outgoingSupplyOrders as &$order) {
            $order['items']   = $this->supplyItemModel->where('order_id', $order['id'])->findAll();
            $order['history'] = $this->statusHistoryModel->getHistory('supply', $order['id']);
        }
        unset($order);

        return view('pesanan/index', [
            'pageTitle'            => 'Pesanan & Transaksi — AgriLink',
            'incomingSalesOrders'  => $incomingSalesOrders,
            'outgoingSupplyOrders' => $outgoingSupplyOrders,
        ]);
    }

    /**
     * Pesanan Masuk Supplier (Saprotan)
     */
    public function supplierPesanan()
    {
        $supplierId = (int) session()->get('user_id');
        $orders = $this->supplyOrderModel->getOrdersBySupplier($supplierId);

        foreach ($orders as &$order) {
            $order['items']   = $this->supplyItemModel->where('order_id', $order['id'])->findAll();
            $order['history'] = $this->statusHistoryModel->getHistory('supply', $order['id']);
        }
        unset($order);

        return view('pesanan/supplier_pesanan', [
            'pageTitle' => 'Kelola Pesanan Saprotan — AgriLink',
            'orders'    => $orders,
        ]);
    }

    /**
     * Pesanan Pembelian Buyer (Hasil Tani)
     */
    public function buyerPesanan()
    {
        $buyerId = (int) session()->get('user_id');
        $orders  = $this->salesOrderModel->getOrdersByBuyer($buyerId);

        foreach ($orders as &$order) {
            $order['items']   = $this->salesItemModel->where('order_id', $order['id'])->findAll();
            $order['history'] = $this->statusHistoryModel->getHistory('sales', $order['id']);
        }
        unset($order);

        return view('pesanan/buyer_pesanan', [
            'pageTitle' => 'Riwayat Pesanan Komoditas — AgriLink',
            'orders'    => $orders,
        ]);
    }

    /**
     * Pembaruan Status Pesanan (PRD Bab 8.1 Status Pesanan 4-Tahap)
     */
    public function updateStatus()
    {
        $userId    = (int) session()->get('user_id');
        $orderType = (string) $this->request->getPost('order_type'); // 'supply' or 'sales'
        $orderId   = (int) $this->request->getPost('order_id');
        $newStatus = (string) $this->request->getPost('new_status');
        $notes     = trim((string) $this->request->getPost('notes'));

        $allowedStatuses = ['menunggu_konfirmasi', 'dikonfirmasi', 'diproses', 'selesai', 'dibatalkan', 'ditolak'];
        if (!in_array($newStatus, $allowedStatuses, true)) {
            return redirect()->back()->with('error', 'Status pesanan tidak valid.');
        }

        if ($orderType === 'supply') {
            $order = $this->supplyOrderModel->find($orderId);
            if (!$order) {
                return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
            }
            $prevStatus = $order['status'];
            $this->supplyOrderModel->update($orderId, ['status' => $newStatus]);
        } elseif ($orderType === 'sales') {
            $order = $this->salesOrderModel->find($orderId);
            if (!$order) {
                return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
            }
            $prevStatus = $order['status'];
            $this->salesOrderModel->update($orderId, ['status' => $newStatus]);
        } else {
            return redirect()->back()->with('error', 'Tipe pesanan tidak valid.');
        }

        // Catat ke log riwayat status
        $this->statusHistoryModel->logStatusChange(
            $orderType,
            $orderId,
            $prevStatus,
            $newStatus,
            $userId,
            $notes ?: 'Pembaruan status transaksi pesanan menjadi ' . strtoupper($newStatus)
        );

        return redirect()->back()->with('success', "Status pesanan <strong>#{$order['order_number']}</strong> berhasil diperbarui menjadi <strong>" . strtoupper(str_replace('_', ' ', $newStatus)) . "</strong>.");
    }
}

