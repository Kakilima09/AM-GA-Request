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
        Schema::create('am_service_kendaraan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('no_polisi');
            $table->string('merk_type');
            $table->integer('km')->nullable();
            $table->text('perbaikan_penggantian')->nullable();
            $table->boolean('oli')->default(false);
            $table->boolean('tune_up')->default(false);
            $table->boolean('rem')->default(false);
            $table->boolean('ac')->default(false);
            $table->text('keluhan')->nullable();
            $table->boolean('kopling')->default(false);
            $table->boolean('lampu')->default(false);
            $table->boolean('accu')->default(false);
            $table->boolean('filter')->default(false);
            $table->boolean('balancing')->default(false);
            $table->boolean('spooring')->default(false);
            $table->boolean('ban')->default(false);
            $table->boolean('wiper')->default(false);
            $table->boolean('overhaul')->default(false);
            $table->text('lain_lain')->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'completed'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('am_service_kendaraan');
    }
};
