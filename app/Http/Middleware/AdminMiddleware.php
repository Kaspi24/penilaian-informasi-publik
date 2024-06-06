<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if ($user->role !== UserRole::ADMIN) {
            return response()->view('notfound');
        }
        return $next($request);
    }
}
