<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\ModuleAccess;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ModuleAccessController extends Controller
{
    // Menampilkan halaman pengaturan modul
    public function index()
    {
        $allModules = ModuleAccess::distinct()
            ->pluck('module_name')
            ->toArray();

        // Jika belum ada data di database
        if (empty($allModules)) {
            $allModules = $this->defaultModules();
        }

        $roles = [
            'admin_am',
            'admin_ga',
            'user'
        ];

        $accesses = ModuleAccess::all()
            ->groupBy('role');

        return view(
            'super-admin.module-access.index',
            compact(
                'allModules',
                'roles',
                'accesses'
            )
        );
    }


    // Menyimpan modul baru
    public function store(Request $request)
    {
        $request->validate([
            'module_name' =>
                'required|string|max:255|unique:module_accesses,module_name',
        ]);


        $roles = [
            'admin_am',
            'admin_ga',
            'user'
        ];


        foreach ($roles as $role) {

            // User default false untuk bast
            $isActive = !(
                $role === 'user'
                && $request->module_name === 'bast'
            );


            ModuleAccess::create([
                'role' => $role,
                'module_name' => $request->module_name,
                'is_active' => $isActive,
            ]);

        }


        return redirect()
            ->route('super-admin.module-access.index')
            ->with(
                'success',
                'Modul berhasil ditambahkan.'
            );
    }


    // Memperbarui akses modul
    public function update(Request $request)
    {
        $roles = [
            'admin_am',
            'admin_ga',
            'user'
        ];


        // Ambil modul dari database
        $allModules = ModuleAccess::distinct()
            ->pluck('module_name')
            ->toArray();


        // Jika database belum memiliki modul,
        // gunakan modul default
        if (empty($allModules)) {

            $allModules = $this->defaultModules();

        }


        foreach ($roles as $role) {

            foreach ($allModules as $module) {

                $isActive = isset(
                    $request->access[$role][$module]
                ) ? 1 : 0;


                ModuleAccess::updateOrCreate(
                    [
                        'role' => $role,
                        'module_name' => $module,
                    ],
                    [
                        'is_active' => $isActive,
                    ]
                );

            }

        }


        return redirect()
            ->route('super-admin.module-access.index')
            ->with(
                'success',
                'Pengaturan akses modul berhasil diperbarui.'
            );
    }


    // Menghapus modul
    public function destroy($moduleName)
    {
        ModuleAccess::where(
            'module_name',
            $moduleName
        )->delete();


        return redirect()
            ->route('super-admin.module-access.index')
            ->with(
                'success',
                'Modul berhasil dihapus.'
            );
    }


    // Daftar modul default
    private function defaultModules()
    {
        return [

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
    }
}
