<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class StoreJuryRequest extends FormRequest
{
    protected $errorBag = 'create_jury';

    public function authorize(): bool
    {
        return auth()->check();
    }
    
    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'username'  => ['required', 'string', 'max:255', Rule::unique(User::class)],
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)],
            'password'  => ['required', Password::defaults(), 'confirmed'],
        ];
    }

    public function attributes()
    {
        return [
            'name'      => 'Nama',
            'username'  => 'Username',
            'email'     => 'Email',
            'password'  => 'Password',
        ];
    }
}
