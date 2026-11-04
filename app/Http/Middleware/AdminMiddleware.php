<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        abort_unless(
            $user && in_array($user->role, ['admin', 'superadmin'], true),
            403,
            'Anda tidak memiliki akses ke halaman ini.'
        );

        return $next($request);
    }
}
