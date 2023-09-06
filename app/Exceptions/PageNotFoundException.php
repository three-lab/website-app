<?php

namespace App\Exceptions;

use Exception;

class PageNotFoundException extends Exception
{
    public function __construct()
    {
        http_response_code(404);
        echo "Not Found";
    }
}
