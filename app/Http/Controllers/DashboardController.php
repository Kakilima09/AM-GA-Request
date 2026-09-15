<?php

namespace App\Http\Controllers;

use App\Models\AmServiceKendaraan;
use App\Models\AmServiceFaNonKendaraan;
use App\Models\AmSewa;
use App\Models\AmRenovasiRelokasi;
use App\Models\FaBaru;
use App\Models\FaPenghapusan;
use App\Models\FaPenjualan;
use App\Models\FaMutasi;
use App\Models\Bast;
use App\Models\GaBarang;
use App\Models\GaJasaLembur;
use App\Models\GaRuangMeeting;
use App\Models\ModuleAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        // Data statistik berdasarkan role
        $stats = $this->getStatsByRole($role);

        // Data untuk chart
        $chartData = $this->getChartData($role);

        // Recent activities
        $recentActivities = $this->getRecentActivities($role);

        // Data khusus untuk Super Admin
        $moduleList = [];
        $totalUsers = 0;
        if ($role === 'super_admin') {
            $moduleList = ModuleAccess::distinct('module_name')->pluck('module_name')->toArray();
            $totalUsers = \App\Models\User::count();
        }

        // Data untuk Admin AM
        $amStats = [];
        if ($role === 'admin_am' || $role === 'super_admin') {
            $amStats = [
                'total' => $this->countModule('am-service-kendaraan') + 
                           $this->countModule('am-service-fa-non') + 
                           $this->countModule('am-sewa') + 
                           $this->countModule('am-renovasi'),
                'pending' => $this->countModule('am-service-kendaraan', 'pending') + 
                             $this->countModule('am-service-fa-non', 'pending') + 
                             $this->countModule('am-sewa', 'pending') + 
                             $this->countModule('am-renovasi', 'pending'),
                'completed' => $this->countModule('am-service-kendaraan', 'completed') + 
                               $this->countModule('am-service-fa-non', 'completed') + 
                               $this->countModule('am-sewa', 'completed') + 
                               $this->countModule('am-renovasi', 'completed'),
            ];
        }

        // Data untuk Admin GA
        $gaStats = [];
        if ($role === 'admin_ga' || $role === 'super_admin') {
            $gaStats = [
                'total' => $this->countModule('ga-barang') + 
                           $this->countModule('ga-jasa-lembur') + 
                           $this->countModule('ga-ruang-meeting'),
                'pending' => $this->countModule('ga-barang', 'pending') + 
                             $this->countModule('ga-jasa-lembur', 'pending') + 
                             $this->countModule('ga-ruang-meeting', 'pending'),
                'completed' => $this->countModule('ga-barang', 'completed') + 
                               $this->countModule('ga-jasa-lembur', 'completed') + 
                               $this->countModule('ga-ruang-meeting', 'completed'),
            ];
        }

        // Data untuk user biasa
        $userStats = [];
        if ($role === 'user') {
            // Ambil semua pengajuan milik user ini
            $userStats = $this->getUserStats($user->id);
        }

        // Status colors
        $statusColors = [
            'draft' => '#6c757d',
            'pending' => '#ffc107',
            'approved' => '#28a745',
            'rejected' => '#dc3545',
            'completed' => '#17a2b8',
        ];

        return view('dashboard', compact(
            'stats', 'chartData', 'recentActivities', 'moduleList', 
            'statusColors', 'role', 'totalUsers', 'amStats', 'gaStats', 'userStats'
        ));
    }

    private function countModule($moduleName, $status = null)
    {
        $models = [
            'am-service-kendaraan' => AmServiceKendaraan::class,
            'am-service-fa-non' => AmServiceFaNonKendaraan::class,
            'am-sewa' => AmSewa::class,
            'am-renovasi' => AmRenovasiRelokasi::class,
            'fa-baru' => FaBaru::class,
            'fa-penghapusan' => FaPenghapusan::class,
            'fa-penjualan' => FaPenjualan::class,
            'fa-mutasi' => FaMutasi::class,
            'bast' => Bast::class,
            'ga-barang' => GaBarang::class,
            'ga-jasa-lembur' => GaJasaLembur::class,
            'ga-ruang-meeting' => GaRuangMeeting::class,
        ];

        if (!isset($models[$moduleName])) return 0;

        $model = new $models[$moduleName]();
        $query = $model->newQuery();
        if ($status) {
            $query->where('status', $status);
        }
        return $query->count();
    }

    private function getUserStats($userId)
    {
        $stats = [
            'total' => 0,
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
            'draft' => 0,
            'completed' => 0,
        ];

        // Ambil semua modul yang diakses oleh user (berdasarkan role)
        $modules = $this->getModulesForRole(auth()->user()->role);

        foreach ($modules as $moduleName => $modelClass) {
            $model = new $modelClass();
            $stats['total'] += $model->where('user_id', $userId)->count();
            $stats['pending'] += $model->where('user_id', $userId)->where('status', 'pending')->count();
            $stats['approved'] += $model->where('user_id', $userId)->where('status', 'approved')->count();
            $stats['rejected'] += $model->where('user_id', $userId)->where('status', 'rejected')->count();
            $stats['draft'] += $model->where('user_id', $userId)->where('status', 'draft')->count();
            $stats['completed'] += $model->where('user_id', $userId)->where('status', 'completed')->count();
        }

        return $stats;
    }

    private function getStatsByRole($role)
    {
        $stats = [
            'total' => 0,
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
            'draft' => 0,
            'completed' => 0,
            'modules' => []
        ];

        $modules = $this->getModulesForRole($role);

        foreach ($modules as $moduleName => $modelClass) {
            $model = new $modelClass();
            $total = $model->count();
            $pending = $model->where('status', 'pending')->count();
            $approved = $model->where('status', 'approved')->count();
            $rejected = $model->where('status', 'rejected')->count();
            $draft = $model->where('status', 'draft')->count();
            $completed = $model->where('status', 'completed')->count();

            if ($total > 0) {
                $stats['modules'][$moduleName] = [
                    'total' => $total,
                    'pending' => $pending,
                    'approved' => $approved,
                    'rejected' => $rejected,
                    'draft' => $draft,
                    'completed' => $completed,
                ];
                $stats['total'] += $total;
                $stats['pending'] += $pending;
                $stats['approved'] += $approved;
                $stats['rejected'] += $rejected;
                $stats['draft'] += $draft;
                $stats['completed'] += $completed;
            }
        }

        return $stats;
    }

    private function getModulesForRole($role)
    {
        $allModules = [
            'am-service-kendaraan' => AmServiceKendaraan::class,
            'am-service-fa-non' => AmServiceFaNonKendaraan::class,
            'am-sewa' => AmSewa::class,
            'am-renovasi' => AmRenovasiRelokasi::class,
            'fa-baru' => FaBaru::class,
            'fa-penghapusan' => FaPenghapusan::class,
            'fa-penjualan' => FaPenjualan::class,
            'fa-mutasi' => FaMutasi::class,
            'bast' => Bast::class,
            'ga-barang' => GaBarang::class,
            'ga-jasa-lembur' => GaJasaLembur::class,
            'ga-ruang-meeting' => GaRuangMeeting::class,
        ];

        if ($role === 'super_admin') {
            return $allModules;
        }

        $allowedModules = ModuleAccess::where('role', $role)
            ->where('is_active', true)
            ->pluck('module_name')
            ->toArray();

        return array_filter($allModules, function ($key) use ($allowedModules) {
            return in_array($key, $allowedModules);
        }, ARRAY_FILTER_USE_KEY);
    }

    private function getChartData($role)
    {
        $labels = [];
        $pendingData = [];
        $approvedData = [];
        $rejectedData = [];

        $modules = $this->getModulesForRole($role);
        foreach ($modules as $moduleName => $modelClass) {
            $model = new $modelClass();
            $label = ucwords(str_replace('-', ' ', $moduleName));
            $labels[] = $label;
            $pendingData[] = $model->where('status', 'pending')->count();
            $approvedData[] = $model->where('status', 'approved')->count();
            $rejectedData[] = $model->where('status', 'rejected')->count();
        }

        return [
            'labels' => $labels,
            'pending' => $pendingData,
            'approved' => $approvedData,
            'rejected' => $rejectedData,
        ];
    }

    private function getRecentActivities($role)
    {
        $activities = [];
        $modules = $this->getModulesForRole($role);
        $limit = 10;

        foreach ($modules as $moduleName => $modelClass) {
            $model = new $modelClass();
            $items = $model->with('user')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            foreach ($items as $item) {
                $activities[] = [
                    'module' => ucwords(str_replace('-', ' ', $moduleName)),
                    'title' => $this->getItemTitle($moduleName, $item),
                    'status' => $item->status ?? 'unknown',
                    'user' => $item->user->name ?? 'N/A',
                    'created_at' => $item->created_at,
                ];
            }
        }

        usort($activities, function ($a, $b) {
            return $b['created_at'] <=> $a['created_at'];
        });

        return array_slice($activities, 0, $limit);
    }

    private function getItemTitle($moduleName, $item)
    {
        $titles = [
            'am-service-kendaraan' => 'Service: ' . ($item->no_polisi ?? ''),
            'am-service-fa-non' => 'Service FA: ' . ($item->no_fa ?? ''),
            'am-sewa' => 'Sewa: ' . ($item->deskripsi ?? ''),
            'am-renovasi' => 'Renovasi: ' . ($item->lokasi_awal ?? ''),
            'fa-baru' => 'FA Baru: ' . ($item->nama_fa ?? ''),
            'fa-penghapusan' => 'Penghapusan: ' . ($item->nama_fa ?? ''),
            'fa-penjualan' => 'Penjualan: ' . ($item->nama_fa ?? ''),
            'fa-mutasi' => 'Mutasi: ' . ($item->nama_fa ?? ''),
            'bast' => 'BAST: ' . ($item->nama_pemohon ?? ''),
            'ga-barang' => 'Barang: ' . ($item->nama_barang ?? ''),
            'ga-jasa-lembur' => 'Lembur: ' . ($item->pelaksanaan_lembur ?? ''),
            'ga-ruang-meeting' => 'Meeting: ' . ($item->uraian_pemakaian ?? ''),
        ];

        return $titles[$moduleName] ?? 'Activity';
    }
}