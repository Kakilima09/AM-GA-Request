<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasApprovals;
use App\Models\User;

class AmRenovasiRelokasi extends Model
{
    use HasApprovals;
    protected $table = 'am_renovasi_relokasi';
    protected $fillable = [
        'user_id',
        'email_atasan',
        'lokasi_awal',
        'lokasi_tujuan',
        'spesifikasi_awal',
        'spesifikasi_tujuan',
        'status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getApprovalLevels(): array
    {
        return ['l1', 'l2', 'company_head', 'ceo', 'tech_department', 'head_tech', 'admin_am', 'manager_am', 'direktur_am'];
    }
}
