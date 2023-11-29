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
        $total = $this->dashboardRepo->getCountData();
        return view('panel.dashboard', compact('total'));
    }

    public function chartJson()
    {
        return response()->json(
            $this->dashboardRepo->getChartData(),
            200
        );
    }
}
