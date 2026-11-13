<?php

namespace App\Models;

use CodeIgniter\Model;

class EstimateItemModel extends Model
{
    protected $table            = 'estimate_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'estimate_id',
        'product_id',
        'item_name',
        'category',
        'quantity',
        'unit',
        'estimated_unit_price',
        'subtotal',
    ];
}

