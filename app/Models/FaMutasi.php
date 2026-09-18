<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasApprovals;
use App\Models\User;

class FaMutasi extends Model
{
    use HasApprovals;
    protected $table = 'fa_mutasi';
    protected $fillable = [
        'user_id',
        'email_atasan',
        'no_fa',
        'nama_fa',
        'user_awal_departemen',
        'user_akhir_departemen',
        'qty',
        'keterangan',
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
