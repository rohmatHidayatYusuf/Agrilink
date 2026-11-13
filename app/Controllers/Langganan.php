<?php

namespace App\Controllers;

use App\Models\SubscriptionModel;
use App\Models\SubscriptionPlanModel;

class Langganan extends BaseController
{
    protected SubscriptionModel $subModel;
    protected SubscriptionPlanModel $planModel;

    public function __construct()
    {
        $this->subModel  = new SubscriptionModel();
        $this->planModel = new SubscriptionPlanModel();
    }

    public function index()
    {
        $userId = (int) session()->get('user_id');

        $subscription = $this->subModel->getUserSubscription($userId);
        $plans        = $this->planModel->where('status', 'active')->findAll();

        return view('langganan/index', [
            'pageTitle'    => 'Status Langganan — AgriLink',
            'subscription' => $subscription,
            'plans'        => $plans,
        ]);
    }
}

