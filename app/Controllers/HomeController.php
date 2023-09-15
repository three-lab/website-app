<?php

namespace App\Controllers;

use System\Utils\Request;

class HomeController
{
    public function index(Request $request)
    {
        return view('home');
    }

    public function article()
    {
        return [
            'status' => 'ok',
            'message' => 'Success create article data',
        ];
    }
}
