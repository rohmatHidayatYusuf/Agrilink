<?php

namespace App\Models;

use CodeIgniter\Model;

class SalesOrderItemModel extends Model
{
    protected $table            = 'sales_order_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_id',
        'listing_id',
        'product_name_snapshot',
        'quantity',
        'unit',
        'unit_price_snapshot',
        'subtotal',
    ];
}

