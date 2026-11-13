<?php

namespace App\Controllers;

use App\Models\HarvestListingModel;
use App\Models\SalesOrderModel;
use App\Models\SalesOrderItemModel;
use App\Models\OrderStatusHistoryModel;

class Pasar extends BaseController
{
    protected HarvestListingModel $listingModel;
    protected SalesOrderModel $orderModel;
    protected SalesOrderItemModel $orderItemModel;
    protected OrderStatusHistoryModel $statusHistoryModel;

    public function __construct()
    {
        $this->listingModel       = new HarvestListingModel();
        $this->orderModel         = new SalesOrderModel();
        $this->orderItemModel     = new SalesOrderItemModel();
        $this->statusHistoryModel = new OrderStatusHistoryModel();
    }

    /**
     * Halaman Pasar Hasil Tani (Untuk Petani: Melihat listing pasar & listing milik sendiri)
     */
    public function index()
    {
        $userId = (int) session()->get('user_id');
        $search = trim((string) $this->request->getGet('cari'));

        $marketListings = $this->listingModel->getActiveMarketListings($search);
        $myListings     = $this->listingModel->where('seller_id', $userId)->orderBy('id', 'DESC')->findAll();

        return view('pasar/index', [
            'pageTitle'      => 'Pasar Hasil Tani — AgriLink',
            'marketListings' => $marketListings,
            'myListings'     => $myListings,
            'cari'           => $search,
        ]);
    }

    /**
     * Katalog Pasar Hasil Tani (Khusus Role: Buyer)
     */
    public function katalogBuyer()
    {
        $search = trim((string) $this->request->getGet('cari'));
        $listings = $this->listingModel->getActiveMarketListings($search);

        return view('pasar/katalog_buyer', [
            'pageTitle' => 'Katalog Komoditas Segar — AgriLink',
            'listings'  => $listings,
            'cari'      => $search,
        ]);
    }

    /**
     * Buyer membuat pesanan hasil pertanian langsung ke Petani (PRD Bab 6.6 SALE-05)
     */
    public function buatPesanan()
    {
        $buyerId   = (int) session()->get('user_id');
        $listingId = (int) $this->request->getPost('listing_id');
        $quantity  = (float) $this->request->getPost('quantity');
        $address   = trim((string) $this->request->getPost('delivery_address'));
        $notes     = trim((string) $this->request->getPost('notes'));

        if ($listingId <= 0 || $quantity <= 0) {
            return redirect()->back()->with('error', 'Kuantitas pesanan komoditas tidak valid.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $listing = $this->listingModel->find($listingId);

        if (!$listing || $listing['status'] !== 'active') {
            $db->transRollback();
            return redirect()->back()->with('error', 'Listing hasil tani tidak aktif atau sudah habis.');
        }

        if ($quantity < (float) $listing['min_order']) {
            $db->transRollback();
            return redirect()->back()->with('error', "Minimal pembelian komoditas ini adalah {$listing['min_order']} {$listing['unit']}.");
        }

        if ((float) $listing['quantity_available'] < $quantity) {
            $db->transRollback();
            return redirect()->back()->with('error', "Stok hasil tani tidak mencukupi. Tersedia: {$listing['quantity_available']} {$listing['unit']}.");
        }

        // Kurangi stok komoditas di listing
        $newQtyAvailable = (float) $listing['quantity_available'] - $quantity;
        $newStatus = ($newQtyAvailable <= 0.001) ? 'sold_out' : 'active';
        $this->listingModel->update($listingId, [
            'quantity_available' => $newQtyAvailable,
            'status'             => $newStatus,
        ]);

        // Buat Pesanan Penjualan
        $orderNumber  = 'ORD-' . date('Ym') . '-' . strtoupper(bin2hex(random_bytes(3)));
        $unitPrice    = (float) $listing['price_per_unit'];
        $subtotal     = $quantity * $unitPrice;
        $shippingCost = 50000.00; // Flat ongkir pengiriman komoditas standar
        $totalAmount  = $subtotal + $shippingCost;

        $orderId = $this->orderModel->insert([
            'order_number'     => $orderNumber,
            'buyer_id'         => $buyerId,
            'seller_id'        => (int) $listing['seller_id'],
            'subtotal'         => $subtotal,
            'shipping_cost'    => $shippingCost,
            'total_amount'     => $totalAmount,
            'delivery_address' => $address ?: session()->get('user_address'),
            'notes'            => $notes,
            'status'           => 'menunggu_konfirmasi',
        ]);

        // Simpan Snapshot Item Pesanan Hasil Tani (PRD Bab 6.6 SALE-09)
        $this->orderItemModel->insert([
            'order_id'              => $orderId,
            'listing_id'            => $listingId,
            'product_name_snapshot' => $listing['title'],
            'quantity'              => $quantity,
            'unit'                  => $listing['unit'],
            'unit_price_snapshot'   => $unitPrice,
            'subtotal'              => $subtotal,
        ]);

        // Log riwayat status (PRD Bab 8.1)
        $this->statusHistoryModel->logStatusChange(
            'sales',
            $orderId,
            null,
            'menunggu_konfirmasi',
            $buyerId,
            'Pesanan komoditas hasil panen dibuat oleh pembeli.'
        );

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memproses pesanan komoditas.');
        }

        return redirect()->to(base_url('buyer/pesanan'))->with('success', "Pesanan komoditas <strong>{$orderNumber}</strong> berhasil dibuat! Menunggu konfirmasi petani.");
    }
}

