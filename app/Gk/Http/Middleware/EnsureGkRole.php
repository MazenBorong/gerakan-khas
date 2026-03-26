<?php

namespace App\Gk\Http\Middleware;

use App\Gk\Support\RoleReader;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureGkRole
{
    public function handle(Request $request, Closure $next, string $rolesCsv): Response
    {
        $allowed = array_map('trim', explode(',', $rolesCsv));
        $role = RoleReader::forUser($request->user());
        if (! in_array($role, $allowed, true)) {
            abort(403);
        }

        return $next($request);
    }
}
