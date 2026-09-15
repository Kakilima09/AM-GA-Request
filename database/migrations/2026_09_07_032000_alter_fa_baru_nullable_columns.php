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
        Schema::table('fa_baru', function (Blueprint $table) {
            $table->string('no_fa')->nullable()->change();
            $table->string('nama_fa')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fa_baru', function (Blueprint $table) {
            $table->string('no_fa')->nullable(false)->change();
            $table->string('nama_fa')->nullable(false)->change();
        });
    }
};
