<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use SebastianBergmann\Type\TrueType;

class GaRuangMeetingRequest extends FormRequest
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
            'uraian_pemakaian' => 'required|string',
            'no_permintaan' => 'required|string|max:50',
            'qty' => 'nullable|integer|min:0',
            'keterangan' => 'nullable|string',
            'hari_kerja' => 'nullable|integer|min:0',
            'hari_libur' => 'nullable|integer|min:0',
            'jumlah_sdm' => 'nullable|integer|min:0',
            'hari_tanggal' => 'required|date',
            'waktu' => 'required|date_format:H:i',
        ];
    }
}
