<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FaBaruRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'kategori' => 'required|in:umum,it,kendaraan',
            'tipe_kendaraan' => 'nullable|string|max:50|required_if:kategori,kendaraan',
            'is_cop' => 'sometimes|boolean',

            // Items
            'items' => 'required|array|min:1',
            'items.*.no_fa' => 'required|string|max:50',
            'items.*.nama_fa' => 'required|string|max:255',
            'items.*.spesifikasi' => 'nullable|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.estimasi_harga' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'kategori.required' => 'Kategori FA wajib dipilih.',
            'kategori.in' => 'Kategori FA tidak valid.',
            'tipe_kendaraan.required_if' => 'Tipe kendaraan wajib diisi jika kategori adalah kendaraan.',
            'items.required' => 'Minimal satu item FA harus ditambahkan.',
            'items.*.no_fa.required' => 'Nomor FA wajib diisi.',
            'items.*.nama_fa.required' => 'Nama FA wajib diisi.',
            'items.*.qty.required' => 'Jumlah (Qty) wajib diisi.',
            'items.*.qty.min' => 'Jumlah (Qty) minimal 1.',
            'items.*.estimasi_harga.required' => 'Estimasi harga wajib diisi.',
            'items.*.estimasi_harga.min' => 'Estimasi harga tidak boleh negatif.',
        ];
    }
}
