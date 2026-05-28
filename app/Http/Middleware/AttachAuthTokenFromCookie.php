<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AttachAuthTokenFromCookie
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->bearerToken()) {
            $token = $request->cookie('auth_token');

            if ($token) {
                $request->headers->set('Authorization', 'Bearer '.$token);
            }
        }

        return $next($request);
    }
}
