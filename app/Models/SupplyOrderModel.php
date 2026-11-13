<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplyOrderModel extends Model
{
    protected $table            = 'supply_orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_number',
        'buyer_id',
        'supplier_id',
        'subtotal',
        'shipping_cost',
        'total_amount',
        'shipping_address',
        'notes',
        'status',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getOrdersByBuyer(int $buyerId): array
    {
        return $this->select('supply_orders.*, users.nama_lengkap as supplier_name, users.telepon as supplier_phone')
                    ->join('users', 'users.id = supply_orders.supplier_id')
                    ->where('supply_orders.buyer_id', $buyerId)
                    ->orderBy('supply_orders.id', 'DESC')
                    ->findAll();
    }

    public function getOrdersBySupplier(int $supplierId): array
    {
        return $this->select('supply_orders.*, users.nama_lengkap as buyer_name, users.telepon as buyer_phone, users.alamat as buyer_address')
                    ->join('users', 'users.id = supply_orders.buyer_id')
                    ->where('supply_orders.supplier_id', $supplierId)
                    ->orderBy('supply_orders.id', 'DESC')
                    ->findAll();
    }
}

