<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminAuditLogModel extends Model
{
    protected $table            = 'admin_audit_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'admin_id',
        'action',
        'entity_type',
        'entity_id',
        'details',
        'created_at',
    ];

    protected $useTimestamps = false;

    public function logAction(int $adminId, string $action, string $entityType, int $entityId, ?string $details = null): bool
    {
        return (bool) $this->insert([
            'admin_id'    => $adminId,
            'action'      => $action,
            'entity_type' => $entityType,
            'entity_id'   => $entityId,
            'details'     => $details,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);
    }

    public function getRecentLogs(int $limit = 50): array
    {
        return $this->select('admin_audit_logs.*, users.nama_lengkap as admin_name')
                    ->join('users', 'users.id = admin_audit_logs.admin_id')
                    ->orderBy('admin_audit_logs.id', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}

