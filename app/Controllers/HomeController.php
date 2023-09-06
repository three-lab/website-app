<?php

namespace App\Controllers;

use System\Utils\Request;

class HomeController
{
    public function index(Request $request)
    {
        echo $request->fullname;
        return config("app.name");
    }

    public function article()
    {
        return [
            'status' => 'ok',
            'message' => 'Success create article data',
        ];
    }
}
