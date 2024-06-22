<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EmailVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user->role === 'RESPONDENT' && !isset($user->email_verified_at)) {
            return redirect()
                ->route('email-verification')
                ->with('warning', 'Anda belum memverifikasi alamat email. Harap selesaikan verifikasi sebelum memesan layanan!');
        }
        return $next($request);
    }
}
