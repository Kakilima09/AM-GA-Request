<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasApprovals;
use App\Models\User;

class FaPenjualan extends Model
{
    use HasApprovals;
    protected $table = 'fa_penjualan';
    protected $fillable = [
        'user_id',
        'no_fa',
        'nama_fa',
        'keterangan',
        'qty',
        'nbv',
        'harga_jual',
        'jenis',
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
