<?php

namespace App\Http\Controllers;

use System\Support\Facades\Pusher;

class DashboardController
{
    public function index()
    {
        return view('panel.dashboard');
    }
}
