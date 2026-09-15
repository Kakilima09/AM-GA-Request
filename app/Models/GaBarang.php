<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Traits\HasApprovals;

class GaBarang extends Model
{
    use HasApprovals;
    protected $table = 'ga_barang';
    protected $fillable = [
        'user_id',
        'nama_barang',
        'qty',
        'tgl_terima',
        'keterangan',
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
