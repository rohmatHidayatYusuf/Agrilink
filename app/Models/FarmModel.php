<?php

namespace App\Models;

use CodeIgniter\Model;

class FarmModel extends Model
{
    protected $table            = 'farms';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'owner_id',
        'name',
        'location',
        'area_size',
        'area_unit',
        'status',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Dapatkan lahan milik petani beserta jumlah rencana tanam
     */
    public function getFarmsByOwner(int $ownerId): array
    {
        return $this->select('farms.*, COUNT(farm_plans.id) as active_plans_count, COALESCE(SUM(farm_plans.planned_area), 0) as total_used_area')
                    ->join('farm_plans', 'farm_plans.farm_id = farms.id AND farm_plans.status = "active"', 'left')
                    ->where('farms.owner_id', $ownerId)
                    ->where('farms.status', 'active')
                    ->groupBy('farms.id')
                    ->orderBy('farms.id', 'DESC')
                    ->findAll();
    }
}

