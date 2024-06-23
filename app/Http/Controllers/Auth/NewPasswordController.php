<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Models\Auth\PasswordResetToken;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class NewPasswordController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token'     => ['required'],
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $passwordResetToken = PasswordResetToken::firstWhere('token', $request->token);

        if(!$passwordResetToken) {
            return back()->with('failed', 'Permintaan Anda tidak dikenali!');
        } else if($passwordResetToken->expiry_time < Carbon::now()) {
            $passwordResetToken->delete();
            return back()->with('failed', 'Permintaan Anda telah kedaluwarsa!');
        }

        $user = User::firstWhere('email',$passwordResetToken->email);

        if(!$user) { 
            return back()->with('failed', 'Sepertinya akun Anda mengalami perubahan atau tidak terdaftar!');
        } else {
            $user->forceFill([
                'password'      => Hash::make($request->password),
                'remember_token' => Str::random(60),
            ])->save();
            event(new PasswordReset($user));
            $passwordResetToken->delete();
            return redirect()->route('login')->with('success', 'Password Anda telah diatur ulang, silakan login kembali.');
        }
    }
}
