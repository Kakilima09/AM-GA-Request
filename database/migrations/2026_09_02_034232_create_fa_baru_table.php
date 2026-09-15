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
        Schema::create('fa_baru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('no_fa')->unique();
            $table->string('nama_fa');
            $table->enum('kategori', ['umum', 'it', 'kendaraan'])->default('umum');
            $table->string('tipe_kendaraan')->nullable(); // R2, R4, COP
            $table->boolean('is_cop')->default(false);
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'completed'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fa_baru');
        Schema::dropIfExists('fa_baru_items');
    }
};
