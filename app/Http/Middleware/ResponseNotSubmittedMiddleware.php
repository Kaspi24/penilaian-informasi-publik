<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use App\Models\RespondentScore;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseNotSubmittedMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $submission = RespondentScore::firstWhere('respondent_id', $user->id);
        if ($submission->is_done_filling) {
            return redirect()->back()->with(['failed' => 'Anda telah mengirimkan jawaban atau masa pengisian telah berakhir!']);
        }
        return $next($request);
    }
}
