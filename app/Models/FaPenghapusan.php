<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasApprovals;
use App\Models\User;

class FaPenghapusan extends Model
{
    use HasApprovals;
    protected $table = 'fa_penghapusan';
    protected $fillable = [
        'user_id',
        'no_fa',
        'nama_fa',
        'keterangan',
        'qty',
        'nbv',
        'foto_fa',
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
