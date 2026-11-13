<?php

namespace App\Models;

use CodeIgniter\Model;

class FarmPlanModel extends Model
{
    protected $table            = 'farm_plans';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'farm_id',
        'created_by',
        'commodity',
        'planned_area',
        'start_date',
        'expected_harvest_date',
        'status',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Dapatkan rencana tanam beserta nama lahan
     */
    public function getPlansWithFarm(int $userId): array
    {
        return $this->select('farm_plans.*, farms.name as farm_name, farms.area_size as total_farm_size, farms.area_unit')
                    ->join('farms', 'farms.id = farm_plans.farm_id')
                    ->where('farm_plans.created_by', $userId)
                    ->orderBy('farm_plans.id', 'DESC')
                    ->findAll();
    }
}

