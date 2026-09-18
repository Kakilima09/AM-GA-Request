<?php

namespace App\Models;

use App\Traits\HasApprovals;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AmServiceKendaraan extends Model
{
    use HasApprovals;

    protected $table = 'am_service_kendaraan';
    protected $fillable = [
        'user_id',
        'email_atasan',
        'no_polisi',
        'merk_type',
        'km',
        'foto_km',
        'perbaikan_penggantian',
        'oli',
        'tune_up',
        'rem',
        'ac',
        'keluhan',
        'kopling',
        'lampu',
        'accu',
        'filter',
        'balancing',
        'spooring',
        'ban',
        'wiper',
        'overhaul',
        'lain_lain',
        'status'
    ];

    protected $casts = [
        'oli' => 'boolean',
        'tune_up' => 'boolean',
        'rem' => 'boolean',
        'ac' => 'boolean',
        'kopling' => 'boolean',
        'lampu' => 'boolean',
        'accu' => 'boolean',
        'filter' => 'boolean',
        'balancing' => 'boolean',
        'spooring' => 'boolean',
        'ban' => 'boolean',
        'wiper' => 'boolean',
        'overhaul' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getApprovalLevels(): array
    {
        // Aturan bisnis: semua service kendaraan perlu approval L1, L2, dan AM Manager
        $levels = ['l1', 'l2', 'manager_am'];
        return $levels;
    }
}
