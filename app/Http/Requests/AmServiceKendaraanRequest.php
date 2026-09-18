<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AmServiceKendaraanRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'email_atasan' => 'required|email|max:255',
            'no_polisi' => 'required|string|max:20',
            'merk_type' => 'required|string|max:100',
            'km' => 'nullable|integer|min:0',
            'foto_km' => 'nullable|image|max:2048', // max 2MB
            'keluhan' => 'nullable|string',
            'oli' => 'sometimes|boolean',
            'tune_up' => 'sometimes|boolean',
            // ... semua checkbox lainnya
            'lain_lain' => 'nullable|string',
        ];
    }
}
