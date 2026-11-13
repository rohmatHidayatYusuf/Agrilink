<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'supplier_id',
        'name',
        'category',
        'description',
        'price',
        'unit',
        'stock',
        'image',
        'status',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Dapatkan produk aktif untuk katalog marketplace saprotan
     */
    public function getActiveCatalog(?string $category = null, ?string $search = null): array
    {
        $builder = $this->select('products.*, users.nama_lengkap as supplier_name, users.telepon as supplier_phone, users.alamat as supplier_location')
                        ->join('users', 'users.id = products.supplier_id')
                        ->where('products.status', 'active');

        if ($category && $category !== 'all') {
            $builder->where('products.category', $category);
        }

        if ($search) {
            $builder->groupStart()
                    ->like('products.name', $search)
                    ->orLike('products.description', $search)
                    ->groupEnd();
        }

        return $builder->orderBy('products.id', 'DESC')->findAll();
    }
}

