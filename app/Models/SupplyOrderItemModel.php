<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplyOrderItemModel extends Model
{
    protected $table            = 'supply_order_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_id',
        'product_id',
        'product_name_snapshot',
        'quantity',
        'unit',
        'unit_price_snapshot',
        'subtotal',
    ];
}

