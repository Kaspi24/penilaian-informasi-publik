<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetJuryRequest extends FormRequest
{
    protected $errorBag = 'set_jury';

    public function authorize(): bool
    {
        return auth()->check();
    }
    
    public function rules(): array
    {
        return [
            'jury_id'       => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'jury_id'       => 'Juri',
        ];
    }
}
