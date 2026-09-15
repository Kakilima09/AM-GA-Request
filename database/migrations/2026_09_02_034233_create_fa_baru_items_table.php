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
        Schema::create('fa_baru_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fa_baru_id')->constrained('fa_baru')->onDelete('cascade');
            $table->string('no_fa')->nullable();
            $table->string('nama_fa');
            $table->text('spesifikasi')->nullable();
            $table->integer('qty')->default(1);
            $table->decimal('estimasi_harga', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fa_baru_items');
    }
};
