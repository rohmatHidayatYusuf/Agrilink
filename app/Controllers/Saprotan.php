<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\SupplyOrderModel;
use App\Models\SupplyOrderItemModel;
use App\Models\OrderStatusHistoryModel;

class Saprotan extends BaseController
{
    protected ProductModel $productModel;
    protected SupplyOrderModel $orderModel;
    protected SupplyOrderItemModel $orderItemModel;
    protected OrderStatusHistoryModel $statusHistoryModel;

    public function __construct()
    {
        $this->productModel        = new ProductModel();
        $this->orderModel          = new SupplyOrderModel();
        $this->orderItemModel      = new SupplyOrderItemModel();
        $this->statusHistoryModel  = new OrderStatusHistoryModel();
    }

    /**
     * Marketplace Saprotan (Untuk Petani)
     */
    public function index()
    {
        $category = (string) $this->request->getGet('kategori');
        $search   = trim((string) $this->request->getGet('cari'));

        $products = $this->productModel->getActiveCatalog($category, $search);

        return view('saprotan/index', [
            'pageTitle' => 'Marketplace Saprotan — AgriLink',
            'products'  => $products,
            'kategori'  => $category,
            'cari'      => $search,
        ]);
    }

    /**
     * Petani membuat pesanan kebutuhan saprotan ke Supplier
     */
    public function buatPesanan()
    {
        $buyerId   = (int) session()->get('user_id');
        $productId = (int) $this->request->getPost('product_id');
        $quantity  = (float) $this->request->getPost('quantity');
        $address   = trim((string) $this->request->getPost('shipping_address'));
        $notes     = trim((string) $this->request->getPost('notes'));

        if ($productId <= 0 || $quantity <= 0) {
            return redirect()->back()->with('error', 'Produk atau kuantitas pesanan tidak valid.');
        }

        // Ambil produk dan validasi ketersediaan stok (PRD Bab 6.4 SUP-06)
        $db = \Config\Database::connect();
        $db->transStart();

        $product = $this->productModel->find($productId);

        if (!$product || $product['status'] !== 'active') {
            $db->transRollback();
            return redirect()->back()->with('error', 'Produk saprotan tidak aktif atau tidak ditemukan.');
        }

        if ((float) $product['stock'] < $quantity) {
            $db->transRollback();
            return redirect()->back()->with('error', "Stok tidak mencukupi. Sisa stok tersedia: {$product['stock']} {$product['unit']}.");
        }

        // Kurangi stok produk
        $newStock = (float) $product['stock'] - $quantity;
        $this->productModel->update($productId, ['stock' => $newStock]);

        // Buat Pesanan
        $orderNumber  = 'SUP-' . date('Ym') . '-' . strtoupper(bin2hex(random_bytes(3)));
        $unitPrice    = (float) $product['price'];
        $subtotal     = $quantity * $unitPrice;
        $shippingCost = 25000.00; // Flat ongkir pengadaan standar
        $totalAmount  = $subtotal + $shippingCost;

        $orderId = $this->orderModel->insert([
            'order_number'     => $orderNumber,
            'buyer_id'         => $buyerId,
            'supplier_id'      => (int) $product['supplier_id'],
            'subtotal'         => $subtotal,
            'shipping_cost'    => $shippingCost,
            'total_amount'     => $totalAmount,
            'shipping_address' => $address ?: session()->get('user_address'),
            'notes'            => $notes,
            'status'           => 'menunggu_konfirmasi',
        ]);

        // Simpan Snapshot Item Pesanan (PRD Bab 6.4 SUP-08)
        $this->orderItemModel->insert([
            'order_id'              => $orderId,
            'product_id'            => $productId,
            'product_name_snapshot' => $product['name'],
            'quantity'              => $quantity,
            'unit'                  => $product['unit'],
            'unit_price_snapshot'   => $unitPrice,
            'subtotal'              => $subtotal,
        ]);

        // Log riwayat status (PRD Bab 8.1)
        $this->statusHistoryModel->logStatusChange(
            'supply',
            $orderId,
            null,
            'menunggu_konfirmasi',
            $buyerId,
            'Pesanan saprotan dibuat oleh petani.'
        );

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses pesanan.');
        }

        return redirect()->to(base_url('pesanan'))->with('success', "Pesanan saprotan <strong>{$orderNumber}</strong> berhasil dibuat! Menunggu konfirmasi supplier.");
    }

    /**
     * Halaman Kelola Katalog Produk (Role: Supplier)
     */
    public function katalogSupplier()
    {
        $supplierId = (int) session()->get('user_id');
        $products   = $this->productModel->where('supplier_id', $supplierId)->orderBy('id', 'DESC')->findAll();

        return view('saprotan/kelola_supplier', [
            'pageTitle' => 'Katalog Produk Supplier — AgriLink',
            'products'  => $products,
        ]);
    }

    /**
     * Supplier menambah produk baru ke katalog
     */
    public function simpanProduk()
    {
        $supplierId = (int) session()->get('user_id');

        $name     = trim((string) $this->request->getPost('name'));
        $category = trim((string) $this->request->getPost('category')) ?: 'Benih';
        $desc     = trim((string) $this->request->getPost('description'));
        $price    = (float) $this->request->getPost('price');
        $unit     = trim((string) $this->request->getPost('unit')) ?: 'kg';
        $stock    = (float) $this->request->getPost('stock');

        if (empty($name) || $price <= 0 || $stock < 0) {
            return redirect()->back()->withInput()->with('error', 'Nama produk, harga (lebih dari 0), dan stok wajib diisi.');
        }

        $this->productModel->insert([
            'supplier_id' => $supplierId,
            'name'        => $name,
            'category'    => $category,
            'description' => $desc,
            'price'       => $price,
            'unit'        => $unit,
            'stock'       => $stock,
            'status'      => 'active',
        ]);

        return redirect()->to(base_url('supplier/katalog'))->with('success', "Produk <strong>" . esc($name) . "</strong> berhasil ditambahkan ke katalog.");
    }
}

