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
        Schema::create('bast_item_jasa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bast_id')->constrained('bast')->onDelete('cascade');
            $table->date('tanggal_permintaan');
            $table->date('tanggal_selesai');
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bast_item_jasa');
    }
};
