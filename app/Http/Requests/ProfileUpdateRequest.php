<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'username'  => ['required', 'string', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'phone'     => [Rule::requiredIf(Auth::user()->role === 'RESPONDENT'), 'string'],
            'whatsapp'  => [Rule::requiredIf(Auth::user()->role === 'RESPONDENT'), 'string'],
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
        ];
    }
}
