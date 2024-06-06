<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RespondentMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if ($user->role !== UserRole::RESPONDENT) {
            return response()->view('notfound');
        }
        return $next($request);
    }
}
