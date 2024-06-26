<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;

use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use App\Models\Auth\EmailVerificationToken;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Redirect;

class EmailVerificationController extends Controller
{
    public function index()
    {
        if(isset(Auth::user()->email_verified_at)) {
            return Redirect::intended(route('dashboard'));
        }
        
        if(EmailVerificationToken::where('email', Auth::user()->email)->count() < 1) {
            $tokenData = EmailVerificationToken::create([
                'email'         => Auth::user()->email,
                'token'         => strval(rand(111111,999999)),
                'expiry_time'   => now()->addMinutes(60)->format('Y-m-d H:i:s')
            ]);
    
            Mail::to($tokenData->email)
                ->send(new VerifyEmail($tokenData->token, $tokenData->expiry_time));
        }
        
        return view('auth.verify-email');
    }

    public function verify(Request $request): RedirectResponse
    {
        
        $user = User::find(Auth::user()->id);
        if(isset($user->email_verified_at)) {
            return Redirect::intended(route('dashboard'));
        }

        $verificationToken = EmailVerificationToken::query()
            ->where('email', $user->email)
            ->where('expiry_time', '>', Carbon::now())
            ->get();
        $codes = $verificationToken->pluck('token')->toArray();
        
        if(!in_array($request->code, $codes)) {
            session()->flash('failed_to_verify');
            return back();
        } else {
            $user->update([
                'email_verified_at' => now()->format('Y-m-d H:i:s')
            ]);
            EmailVerificationToken::where('email', $user->email)->delete();
            
            return Redirect::route('dashboard')
                ->with("success", "Email anda telah terverifikasi, sekarang anda dapat melakukan pengisian penilaian!");
        }

    }

    public function resend()
    {
        if(isset(Auth::user()->email_verified_at)) {
            return Redirect::intended(route('dashboard'));
        }

        $tokenData = EmailVerificationToken::create([
            'email'         => Auth::user()->email,
            'token'         => strval(rand(111111,999999)),
            'expiry_time'   => now()->addMinutes(60)->format('Y-m-d H:i:s')
        ]);

        Mail::to($tokenData->email)->send(new VerifyEmail($tokenData->token, $tokenData->expiry_time));
        
            session()->flash('success_to_resend');
        return back();
    }
}
