<?php

namespace App\Http\Requests;

use App\Models\LandingPageContent;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'id'            => 'required',
            'name'          => 'required',
            'is_visible'    => 'required',
            // 'image'         => ['required_if:is_visible,1', 'file']
            'image'         => [
                Rule::requiredIf(
                    (
                        LandingPageContent::where('id',$this->id)->pluck('image') == ''
                        ||
                        LandingPageContent::where('id',$this->id)->pluck('image') == NULL
                    )
                    &&
                    $this->is_visible == 1
                ), 
                'file'
            ]
        ];
    }
}
