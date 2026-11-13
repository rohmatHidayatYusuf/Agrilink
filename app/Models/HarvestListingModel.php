<?php

namespace App\Models;

use CodeIgniter\Model;

class HarvestListingModel extends Model
{
    protected $table            = 'harvest_listings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'harvest_record_id',
        'seller_id',
        'title',
        'description',
        'price_per_unit',
        'quantity_available',
        'unit',
        'min_order',
        'status',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getActiveMarketListings(?string $search = null): array
    {
        $builder = $this->select('harvest_listings.*, users.nama_lengkap as seller_name, users.telepon as seller_phone, users.alamat as seller_address, harvest_records.commodity, harvest_records.quality_notes')
                        ->join('users', 'users.id = harvest_listings.seller_id')
                        ->join('harvest_records', 'harvest_records.id = harvest_listings.harvest_record_id')
                        ->where('harvest_listings.status', 'active')
                        ->where('harvest_listings.quantity_available >', 0);

        if ($search) {
            $builder->groupStart()
                    ->like('harvest_listings.title', $search)
                    ->orLike('harvest_records.commodity', $search)
                    ->orLike('harvest_listings.description', $search)
                    ->groupEnd();
        }

        return $builder->orderBy('harvest_listings.id', 'DESC')->findAll();
    }
}

