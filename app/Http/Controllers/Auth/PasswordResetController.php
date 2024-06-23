<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use App\Models\Auth\PasswordResetToken;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }
    
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'email_or_username' => ['required', 'string'],
        ]);

        $user = User::query()
            ->where('email', $request->email_or_username)
            ->orWhere('username', $request->email_or_username)
            ->first();

        if ($user) {
            $tokenData = PasswordResetToken::create([
                'email'         => $user->email,
                'token'         => hash('sha256', $user->email),
                'expiry_time'   => now()->addMinutes(60)->format('Y-m-d H:i:s')
            ]);
            Mail::to($tokenData->email)
                ->send(new ResetPassword($tokenData->token, $tokenData->expiry_time));
            
            return back()->with('status', 'Email berisi tautan untuk mengatur ulang password telah dikirimkan.');
        } else {
            return back()->withInput($request->only('email_or_username'))
                ->withErrors([
                    'email_or_username' => 'Pengguna dengan email atau username tersebut tidak dapat ditemukan.'
                ]);
        }
    }
}
