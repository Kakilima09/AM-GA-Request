<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Traits\HasApprovals;

class GaJasaLembur extends Model
{
    use HasApprovals;
    protected $table = 'ga_jasa_lembur';
    protected $fillable = [
        'user_id',
        'pelaksanaan_lembur',
        'uraian_tugas',
        'hari_kerja',
        'hari_libur',
        'jumlah_sdm',
        'hari_tanggal',
        'waktu',
        'status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getApprovalLevels(): array
    {
        return ['l1', 'admin_ga', 'manager_ga'];
    }
}
