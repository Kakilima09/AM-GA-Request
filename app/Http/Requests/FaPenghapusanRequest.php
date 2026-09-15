<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FaPenghapusanRequest extends FormRequest
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
            'no_fa' => 'required|string|max:50',
            'nama_fa' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'qty' => 'required|integer|min:1',
            'nbv' => 'required|numeric|min:0',
            'foto_fa' => 'nullable|image|max:2048', // jika upload file
        ];
    }
}
