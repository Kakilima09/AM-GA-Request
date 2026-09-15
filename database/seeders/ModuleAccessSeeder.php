<?php

namespace Database\Seeders;

use App\Models\ModuleAccess;
use Illuminate\Database\Seeder;

class ModuleAccessSeeder extends Seeder
{
    public function run()
    {
        $modules = [
            'am-service-kendaraan',
            'am-service-fa-non',
            'am-sewa',
            'am-renovasi',
            'fa-baru',
            'fa-penghapusan',
            'fa-penjualan',
            'fa-mutasi',
            'bast',
            'ga-barang',
            'ga-jasa-lembur',
            'ga-ruang-meeting',
        ];

        $roles = ['admin_am', 'admin_ga'];

        foreach ($roles as $role) {
            foreach ($modules as $module) {
                // Default: admin_am dapat semua AM & FA & BAST, admin_ga dapat GA & BAST
                $isActive = false;
                if ($role === 'admin_am' && in_array($module, [
                    'am-service-kendaraan', 'am-service-fa-non', 'am-sewa', 'am-renovasi',
                    'fa-baru', 'fa-penghapusan', 'fa-penjualan', 'fa-mutasi', 'bast'
                ])) {
                    $isActive = true;
                }
                if ($role === 'admin_ga' && in_array($module, [
                    'ga-barang', 'ga-jasa-lembur', 'ga-ruang-meeting', 'bast'
                ])) {
                    $isActive = true;
                }

                ModuleAccess::create([
                    'role' => $role,
                    'module_name' => $module,
                    'is_active' => $isActive,
                ]);
            }
        }
    }
}
