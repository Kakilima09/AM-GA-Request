<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaBaruItem extends Model
{
    protected $table = 'fa_baru_items';

    protected $fillable = [
        'fa_baru_id',
        'no_fa',
        'nama_fa',
        'spesifikasi',
        'qty',
        'estimasi_harga',
    ];

    public function faBaru()
    {
        return $this->belongsTo(FaBaru::class);
    }
}
