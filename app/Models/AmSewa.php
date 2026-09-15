<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasApprovals;

class AmSewa extends Model
{
    use HasApprovals;
    protected $table = 'am_sewa';
    protected $fillable = [
        'user_id',
        'deskripsi',
        'lokasi',
        'durasi',
        'biaya',
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
