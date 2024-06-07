<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfileCompletedMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if(
            $user->role == "RESPONDENT" && (
                $user->email == null || 
                $user->phone == null || 
                $user->whatsapp == null || 
                $user->work_unit->head_name == null ||
                $user->work_unit->phone == null ||
                $user->work_unit->email == null
            )
        ) {
            return redirect()->route('dashboard')
                ->with('warning','Anda belum melengkapi profil anda!');
        }
        return $next($request);
    }
}
