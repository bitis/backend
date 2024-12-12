<?php

namespace App\Http\Middleware;

use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class RequestsAreStateful extends EnsureFrontendRequestsAreStateful
{
    protected function frontendMiddleware(): array
    {
        $middleware = [];

        array_unshift($middleware, function ($request, $next) {
            $request->attributes->set('sanctum', true);

            return $next($request);
        });

        return $middleware;
    }
}
