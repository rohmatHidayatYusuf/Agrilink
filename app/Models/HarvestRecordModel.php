<?php

namespace App\Models;

use CodeIgniter\Model;

class HarvestRecordModel extends Model
{
    protected $table            = 'harvest_records';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'farm_plan_id',
        'farmer_id',
        'commodity',
        'harvest_date',
        'quantity',
        'quantity_sold',
        'unit',
        'quality_notes',
        'storage_location',
        'status',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getRecordsWithPlan(int $farmerId): array
    {
        return $this->select('harvest_records.*, farm_plans.commodity as plan_commodity, farms.name as farm_name, (harvest_records.quantity - harvest_records.quantity_sold) as quantity_remaining')
                    ->join('farm_plans', 'farm_plans.id = harvest_records.farm_plan_id')
                    ->join('farms', 'farms.id = farm_plans.farm_id')
                    ->where('harvest_records.farmer_id', $farmerId)
                    ->orderBy('harvest_records.id', 'DESC')
                    ->findAll();
    }
}

