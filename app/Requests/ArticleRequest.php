<?php

namespace App\Requests;

use System\Components\Request;

class ArticleRequest extends Request
{
    protected function rules(): array
    {
        return [
            'title' => 'required',
        ];
    }
}
