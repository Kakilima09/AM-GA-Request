<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BastRequest extends FormRequest
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
        $rules = [
            'tipe' => 'required|in:fa,jasa',
            'tanggal' => 'required|date',
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'required|exists:departments,id',
            'nama_pemohon' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'diserahkan_oleh' => 'required|string|max:255',
            'diterima_oleh' => 'required|string|max:255',
            'mengetahui' => 'nullable|string|max:255',
            'menyetujui' => 'nullable|string|max:255',
        ];

        // Penerimaan (boolean)
        $penerimaanFields = [
            'penerimaan_fa', 'penerimaan_kendaraan_r2_r4', 'penerimaan_elektronik_it',
            'penerimaan_peralatan_kantor', 'penerimaan_lainnya',
            'jasa_service_kendaraan', 'jasa_renovasi', 'jasa_service_fa_non', 'jasa_lainnya'
        ];
        foreach ($penerimaanFields as $field) {
            $rules[$field] = 'sometimes|boolean';
        }

        // Items FA
        $rules['items_fa'] = 'nullable|array';
        $rules['items_fa.*.no_fa'] = 'required|string|max:50';
        $rules['items_fa.*.merk_type_spesifikasi'] = 'required|string|max:255';
        $rules['items_fa.*.qty'] = 'required|integer|min:1';
        $rules['items_fa.*.kondisi'] = 'required|in:baik,rusak';
        $rules['items_fa.*.keterangan'] = 'nullable|string';

        // Items Jasa
        $rules['items_jasa'] = 'nullable|array';
        $rules['items_jasa.*.tanggal_permintaan'] = 'required|date';
        $rules['items_jasa.*.tanggal_selesai'] = 'required|date|after_or_equal:items_jasa.*.tanggal_permintaan';
        $rules['items_jasa.*.keterangan'] = 'required|string';

        return $rules;
    }

    public function messages()
    {
        return [
            'items_jasa.*.tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal permintaan.',
        ];
    }
}
