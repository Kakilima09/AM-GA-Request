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
        Schema::create('bast_item_fa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bast_id')->constrained('bast')->onDelete('cascade');
            $table->string('no_fa');
            $table->text('merk_type_spesifikasi');
            $table->integer('qty');
            $table->enum('kondisi', ['baik', 'rusak']);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bast_item_fa');
    }
};
