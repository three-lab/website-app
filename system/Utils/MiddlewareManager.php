<?php

namespace System\Utils;

use System\Components\Request;

class MiddlewareManager
{
    public function __construct(
        private array $aliasses,
        private array $middlewares,
        private $nextAction
    ) {}

    public function handle(Request $request)
    {
        $next = $this->nextAction;

        foreach(array_reverse($this->middlewares) as $middleware)
            $next = $this->createClosure(
                array_key_exists($middleware, $this->aliasses) ?
                    $this->aliasses[$middleware] :
                    $middleware,
                $next
            );

        return $next($request);
    }

    private function createClosure(string $middleware, callable $next): callable
    {
        return function($request) use($middleware, $next) {
            return (new $middleware)->handle($request, $next);
        };
    }
}
