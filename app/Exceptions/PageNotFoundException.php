<?php

namespace App\Exceptions;

class PageNotFoundException
{
    public function __construct()
    {
        echo "Not Found";
    }
}
