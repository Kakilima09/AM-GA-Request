<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bast;

class BastItemFa extends Model
{
    protected $table = 'bast_item_fa';
    protected $fillable = [
        'bast_id',
        'no_fa',
        'merk_type_spesifikasi',
        'qty',
        'kondisi',
        'keterangan'
    ];

    public function bast()
    {
        return $this->belongsTo(Bast::class);
    }
}
