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
        'email_atasan',
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

        // Pembelian FA di atas Rp 10.000.000 harus disetujui Direktur dan CEO
        if ($this->estimasi_harga > 10000000) {
            $levels[] = 'direktur_am';
            $levels[] = 'ceo';
        }

        if ($this->kategori === 'it') {
            $levels[] = 'it_department';
        }

        if ($this->tipe_kendaraan === 'R4' && $this->is_cop) {
            $levels[] = 'ceo';
        }

        return array_values(array_unique($levels));
    }
    public function items()
    {
        return $this->hasMany(FaBaruItem::class);
    }
}
