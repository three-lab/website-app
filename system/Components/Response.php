<?php

namespace System\Components;

class Response
{
    private $code;
    private array|string $content;

    public function json(array $data, $code)
    {
        $this->code = $code;
        $this->content = $data;
    }

    public function __destruct()
    {
        http_response_code($this->code);

        if(is_array($this->content)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($this->content);
        }

        if(is_string($this->content))
            echo $this->content;

        exit;
    }
}
