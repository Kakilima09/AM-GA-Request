<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GaBarangRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'nama_barang' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:500',
            // tgl_terima tidak usah di-validate karena diisi otomatis
        ];
    }
}
