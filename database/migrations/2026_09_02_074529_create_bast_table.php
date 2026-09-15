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
        Schema::create('bast', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe', ['fa', 'jasa']);
            $table->date('tanggal');
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('nama_pemohon');
            $table->string('jabatan');
            $table->foreignId('company_id')->nullable()->constrained();
            $table->foreignId('department_id')->nullable()->constrained();
            // Penerimaan FA
            $table->boolean('penerimaan_fa')->default(false);
            $table->boolean('penerimaan_kendaraan_r2_r4')->default(false);
            $table->boolean('penerimaan_elektronik_it')->default(false);
            $table->boolean('penerimaan_peralatan_kantor')->default(false);
            $table->boolean('penerimaan_lainnya')->default(false);
            // Penerimaan Jasa
            $table->boolean('jasa_service_kendaraan')->default(false);
            $table->boolean('jasa_renovasi')->default(false);
            $table->boolean('jasa_service_fa_non')->default(false);
            $table->boolean('jasa_lainnya')->default(false);
            // Penandatangan
            $table->string('diserahkan_oleh')->nullable();
            $table->string('diterima_oleh')->nullable();
            $table->string('mengetahui')->nullable();
            $table->string('menyetujui')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bast');
    }
};
