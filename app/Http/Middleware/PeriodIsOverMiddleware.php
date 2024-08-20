<?php

namespace App\Http\Middleware;

use Closure;
use DateTime;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use App\Models\RespondentScore;
use Symfony\Component\HttpFoundation\Response;

class PeriodIsOverMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $date_today = new DateTime();
        $date_deadline = new DateTime('2024-08-17');
        if ($date_today > $date_deadline) {
            return redirect()->back()->with(['failed' => 'Masa pengisian telah berakhir!']);
        }
        return $next($request);
    }
}
