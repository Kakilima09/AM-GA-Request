<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasApprovals;
use App\Models\User;
use App\Models\FaBaruItem;

class FaBaru extends Model
{
    use HasApprovals;

    protected $table = 'fa_baru';

    protected $fillable = [
        'user_id',
        'no_fa',
        'nama_fa',
        'merk_type_spesifikasi',
        'qty',
        'estimasi_harga',
        'kategori',
        'tipe_kendaraan',
        'is_cop',
        'status'
    ];

    protected $casts = [
        'is_cop' => 'boolean',
        'estimasi_harga' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Tentukan level approval yang diperlukan berdasarkan aturan bisnis.
     */
    public function getApprovalLevels(): array
    {
        $levels = ['l1', 'l2']; // level dasar (Atasan Level 1 & 2)

        if ($this->estimasi_harga > 10000000) {
            $levels[] = 'company_head';
        }

        if ($this->kategori === 'it') {
            $levels[] = 'it_department';
        }

        if ($this->tipe_kendaraan === 'R4' && $this->is_cop) {
            $levels[] = 'ceo';
        }

        // Tambahkan level lain jika diperlukan (misal: direktur_am, manager_am, dll.)
        // $levels[] = 'direktur_am';

        return $levels;
    }
    public function items()
    {
        return $this->hasMany(FaBaruItem::class);
    }
}
