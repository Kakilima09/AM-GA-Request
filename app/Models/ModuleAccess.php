<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleAccess extends Model
{
    protected $fillable = [
        'role',
        'module_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope untuk mengecek apakah sebuah modul aktif untuk role tertentu
     */
    public function scopeActiveForRole($query, $role, $moduleName)
    {
        return $query->where('role', $role)
                     ->where('module_name', $moduleName)
                     ->where('is_active', true);
    }

    /**
     * Cek apakah user memiliki akses ke modul tertentu
     */
    public static function hasAccess($role, $moduleName)
    {
        return self::where('role', $role)
                   ->where('module_name', $moduleName)
                   ->where('is_active', true)
                   ->exists();
    }
}
