<?php

namespace App\Models;

use CodeIgniter\Model;

class SalesOrderModel extends Model
{
    protected $table            = 'sales_orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_number',
        'buyer_id',
        'seller_id',
        'subtotal',
        'shipping_cost',
        'total_amount',
        'delivery_address',
        'notes',
        'status',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getOrdersByBuyer(int $buyerId): array
    {
        return $this->select('sales_orders.*, users.nama_lengkap as seller_name, users.telepon as seller_phone')
                    ->join('users', 'users.id = sales_orders.seller_id')
                    ->where('sales_orders.buyer_id', $buyerId)
                    ->orderBy('sales_orders.id', 'DESC')
                    ->findAll();
    }

    public function getOrdersBySeller(int $sellerId): array
    {
        return $this->select('sales_orders.*, users.nama_lengkap as buyer_name, users.telepon as buyer_phone, users.alamat as buyer_address')
                    ->join('users', 'users.id = sales_orders.buyer_id')
                    ->where('sales_orders.seller_id', $sellerId)
                    ->orderBy('sales_orders.id', 'DESC')
                    ->findAll();
    }
}

