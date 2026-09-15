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
        Schema::create('ga_jasa_lembur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->text('pelaksanaan_lembur');
            $table->text('uraian_tugas');
            $table->integer('hari_kerja')->default(0);
            $table->integer('hari_libur')->default(0);
            $table->integer('jumlah_sdm')->default(0);
            $table->date('hari_tanggal');
            $table->time('waktu');
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'completed'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ga_jasa_lembur');
    }
};
