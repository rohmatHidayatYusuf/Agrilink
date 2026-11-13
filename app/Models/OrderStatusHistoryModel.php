<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderStatusHistoryModel extends Model
{
    protected $table            = 'order_status_histories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_type',
        'order_id',
        'previous_status',
        'new_status',
        'notes',
        'changed_by',
        'created_at',
    ];

    protected $useTimestamps = false;

    public function logStatusChange(string $orderType, int $orderId, ?string $previousStatus, string $newStatus, int $changedBy, ?string $notes = null): bool
    {
        return (bool) $this->insert([
            'order_type'      => $orderType,
            'order_id'        => $orderId,
            'previous_status' => $previousStatus,
            'new_status'      => $newStatus,
            'notes'           => $notes,
            'changed_by'      => $changedBy,
            'created_at'      => date('Y-m-d H:i:s'),
        ]);
    }

    public function getHistory(string $orderType, int $orderId): array
    {
        return $this->select('order_status_histories.*, users.nama_lengkap as changed_by_name')
                    ->join('users', 'users.id = order_status_histories.changed_by')
                    ->where('order_status_histories.order_type', $orderType)
                    ->where('order_status_histories.order_id', $orderId)
                    ->orderBy('order_status_histories.id', 'ASC')
                    ->findAll();
    }
}

