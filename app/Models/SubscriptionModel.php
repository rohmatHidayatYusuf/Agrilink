<?php

namespace App\Models;

use CodeIgniter\Model;

class SubscriptionModel extends Model
{
    protected $table            = 'subscriptions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'plan_id',
        'status',
        'start_at',
        'end_at',
    ];

    protected $useTimestamps = false;

    public function getUserSubscription(int $userId): ?array
    {
        return $this->select('subscriptions.*, subscription_plans.name as plan_name, subscription_plans.features, subscription_plans.price, subscription_plans.billing_period')
                    ->join('subscription_plans', 'subscription_plans.id = subscriptions.plan_id')
                    ->where('subscriptions.user_id', $userId)
                    ->orderBy('subscriptions.id', 'DESC')
                    ->first();
    }
}

