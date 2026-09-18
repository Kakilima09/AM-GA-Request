<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FaMutasiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'email_atasan' => 'required|email|max:255',
            'no_fa' => 'required|string|max:50',
            'nama_fa' => 'required|string|max:255',
            'user_awal_departemen' => 'required|string|max:255',
            'user_akhir_departemen' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
            'keterangan' => 'required|string',
        ];
    }
}
