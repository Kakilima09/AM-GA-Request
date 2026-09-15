<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('am_service_kendaraan', function (Blueprint $table) {
            $table->string('foto_km')->nullable()->after('km');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('am_service_kendaraan', function (Blueprint $table) {
            $table->dropColumn('foto_km');
        });
    }
};
