<?php

namespace App\Controllers;

use System\Components\Request;

class HomeController
{
    public function index(Request $request)
    {
        return view('home');
    }
}
