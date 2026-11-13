<?php

namespace App\Models;

use CodeIgniter\Model;

class EstimateModel extends Model
{
    protected $table            = 'estimates';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'farm_plan_id',
        'created_by',
        'total_estimated_cost',
        'status',
        'notes',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Dapatkan estimasi beserta rencana lahan dan item
     */
    public function getEstimatesWithDetails(int $userId): array
    {
        return $this->select('estimates.*, farm_plans.commodity, farm_plans.planned_area, farms.name as farm_name')
                    ->join('farm_plans', 'farm_plans.id = estimates.farm_plan_id')
                    ->join('farms', 'farms.id = farm_plans.farm_id')
                    ->where('estimates.created_by', $userId)
                    ->orderBy('estimates.id', 'DESC')
                    ->findAll();
    }
}

