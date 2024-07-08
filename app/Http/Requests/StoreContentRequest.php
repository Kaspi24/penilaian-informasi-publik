<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContentRequest extends FormRequest
{
    protected $errorBag = 'create_content';

    public function authorize(): bool
    {
        return auth()->check();
    }
    
    public function rules(): array
    {
        return [
            'name'          => 'required',
            'is_visible'    => 'required',
            'image'         => 'required|file'
        ];
    }

    public function attributes()
    {
        return [
            'is_visible'    => 'Visibilitas',
            'name'          => 'Judul Konten',
            'image'         => 'Gambar Konten'
        ];
    }
}
