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

        foreach(array_reverse($this->middlewares) as $middleware) {
            $exp = explode(':', $middleware);
            $param = $exp[1] ?? null;
            $middleware = array_key_exists($exp[0], $this->aliasses) ?
                $this->aliasses[$exp[0]] : $exp[0];

            $next = $this->createClosure($middleware, $next, $param);
        }

        return $next($request);
    }

    private function createClosure(string $middleware, callable $next, ?string $param): callable
    {
        return function($request) use($middleware, $next, $param) {
            return (new $middleware)->handle($request, $next, $param);
        };
    }
}
