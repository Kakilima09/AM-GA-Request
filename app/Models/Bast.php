<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Company;
use App\Models\Department;
use App\Models\BastItemFa;
use App\Models\BastItemJasa;

class Bast extends Model
{
    protected $table = 'bast'; // Tambahkan ini!

    protected $fillable = [
        'tipe',
        'tanggal',
        'user_id',
        'company_id',
        'department_id',
        'nama_pemohon',
        'jabatan',
        'penerimaan_fa',
        'penerimaan_kendaraan_r2_r4',
        'penerimaan_elektronik_it',
        'penerimaan_peralatan_kantor',
        'penerimaan_lainnya',
        'jasa_service_kendaraan',
        'jasa_renovasi',
        'jasa_service_fa_non',
        'jasa_lainnya',
        'diserahkan_oleh',
        'diterima_oleh',
        'mengetahui',
        'menyetujui',
        'ttd_diserahkan',
        'ttd_diterima',
        'ttd_mengetahui',
        'ttd_menyetujui',
        'status',
    ];

    protected $casts = [
        'penerimaan_fa' => 'boolean',
        'penerimaan_kendaraan_r2_r4' => 'boolean',
        'penerimaan_elektronik_it' => 'boolean',
        'penerimaan_peralatan_kantor' => 'boolean',
        'penerimaan_lainnya' => 'boolean',
        'jasa_service_kendaraan' => 'boolean',
        'jasa_renovasi' => 'boolean',
        'jasa_service_fa_non' => 'boolean',
        'jasa_lainnya' => 'boolean',
        'status' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function itemsFa()
    {
        return $this->hasMany(BastItemFa::class);
    }

    public function itemsJasa()
    {
        return $this->hasMany(BastItemJasa::class);
    }
}
