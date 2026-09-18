<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan email atasan (approval level 1) pada semua tabel pengajuan,
     * serta estimasi_harga pada tabel fa_baru untuk perhitungan threshold.
     */
    public function up(): void
    {
        $tables = [
            'am_sewa',
            'am_service_kendaraan',
            'am_service_fa_non_kendaraan',
            'am_renovasi_relokasi',
            'fa_baru',
            'fa_penghapusan',
            'fa_penjualan',
            'fa_mutasi',
            'ga_barang',
            'ga_jasa_lembur',
            'ga_ruang_meeting',
        ];

        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'email_atasan')) {
                continue;
            }
            Schema::table($table, function (Blueprint $t) {
                $t->string('email_atasan')->nullable()->after('user_id');
            });
        }

        if (!Schema::hasColumn('fa_baru', 'estimasi_harga')) {
            Schema::table('fa_baru', function (Blueprint $t) {
                $t->decimal('estimasi_harga', 15, 2)->nullable()->after('is_cop');
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'am_sewa',
            'am_service_kendaraan',
            'am_service_fa_non_kendaraan',
            'am_renovasi_relokasi',
            'fa_baru',
            'fa_penghapusan',
            'fa_penjualan',
            'fa_mutasi',
            'ga_barang',
            'ga_jasa_lembur',
            'ga_ruang_meeting',
        ];

        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'email_atasan')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropColumn('email_atasan');
                });
            }
        }

        if (Schema::hasColumn('fa_baru', 'estimasi_harga')) {
            Schema::table('fa_baru', function (Blueprint $t) {
                $t->dropColumn('estimasi_harga');
            });
        }
    }
};