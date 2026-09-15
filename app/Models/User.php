<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Approval;
use App\Models\AmServiceKendaraan;
use App\Models\AmServiceFaNonKendaraan;
use App\Models\AmRenovasiRelokasi;
use App\Models\AmSewa;
use App\Models\GaBarang;
use App\Models\GaJasaLembur;
use App\Models\GaRuangMeeting;
use App\Models\FaBaru;
use App\Models\FaMutasi;
use App\Models\FaPenghapusan;
use App\Models\FaPenjualan;
use App\Models\Company;
use App\Models\Department;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'avatar',
        'name',
        'email',
        'password',
        'company_id',
        'department_id',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canApprove($model)
    {
        $nextLevel = $model->getNextPendingLevel();
        if (!$nextLevel) return false;

        return approvalLevelForRole($this->role) === $nextLevel;
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }

    public function amServiceKendaraan()
    {
        return $this->hasMany(AmServiceKendaraan::class);
    }
    public function amServiceNonKendaraan()
    {
        return $this->hasMany(AmServiceFaNonKendaraan::class);
    }
    public function amRenovasiRelokasi()
    {
        return $this->hasMany(AmRenovasiRelokasi::class);
    }
    public function amSewa()
    {
        return $this->hasMany(AmSewa::class);
    }
    public function gaBarang()
    {
        return $this->hasMany(GaBarang::class);
    }
    public function gaJasaLembur()
    {
        return $this->hasMany(GaJasaLembur::class);
    }
    public function gaRuangMeeting()
    {
        return $this->hasMany(GaRuangMeeting::class);
    }
    public function faBaru()
    {
        return $this->hasMany(FaBaru::class);
    }
    public function faMutasi()
    {
        return $this->hasMany(FaMutasi::class);
    }
    public function faPenghapusan()
    {
        return $this->hasMany(FaPenghapusan::class);
    }
    public function faPenjualan()
    {
        return $this->hasMany(FaPenjualan::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : asset('assets/images/users/default.png');
    }
}
