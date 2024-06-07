<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\WorkUnit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WorkUnitUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'head_name' => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(WorkUnit::class)->ignore($this->user()->work_unit_id)],
            'phone'     => ['required', 'string'],
        ];
    }
}
