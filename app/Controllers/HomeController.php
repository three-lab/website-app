<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
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
