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
        Schema::create('module_accesses', function (Blueprint $table) {
            $table->id();
            $table->string('role'); // admin_am, admin_ga, user, dll.
            $table->string('module_name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Unique constraint agar tidak ada duplikasi role + module
            $table->unique(['role', 'module_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_accesses');
    }
};
