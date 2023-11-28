<?php

namespace App\Http\Controllers;

use App\Repos\DashboardRepo;
use System\Support\Facades\Pusher;

class DashboardController
{
    private DashboardRepo $dashboardRepo;

    public function __construct()
    {
        $this->dashboardRepo = new DashboardRepo;
    }

    public function index()
    {
        return view('panel.dashboard');
    }

    public function chartJson()
    {
        $this->dashboardRepo->getChartData();
    }
}
