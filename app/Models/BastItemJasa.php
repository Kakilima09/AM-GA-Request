<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bast;

class BastItemJasa extends Model
{
    protected $table = 'bast_item_jasa';
    protected $fillable = [
        'bast_id',
        'tanggal_permintaan',
        'tanggal_selesai',
        'keterangan'
    ];

    public function bast()
    {
        return $this->belongsTo(Bast::class);
    }
}
