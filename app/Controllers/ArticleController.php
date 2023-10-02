<?php

namespace App\Controllers;

use App\Requests\ArticleRequest;

class ArticleController
{
    public function create()
    {
        return view('article/create');
    }

    public function store(ArticleRequest $request, $id)
    {
        return [];
    }
}
