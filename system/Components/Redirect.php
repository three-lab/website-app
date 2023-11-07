<?php

namespace System\Components;

use Josantonius\Session\Facades\Session;
use System\Components\Request;

class Redirect
{
    private string $route;

    public function __construct(string $route = '')
    {
        $this->route = $route;
    }

    public function back(?string $param = null)
    {
        $this->route = $_SERVER['HTTP_REFERER'] . $param;
        return $this;
    }

    public function with(string $name, $value)
    {
        Session::set($name, $value);
        return $this;
    }

    public function withInput()
    {
        Session::set('prev_input', (new Request)->all());
        return $this;
    }

    public function __destruct()
    {
        header("Location: {$this->route}");
        exit;
    }
}
