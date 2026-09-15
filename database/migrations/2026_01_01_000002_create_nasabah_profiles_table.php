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
        Schema::create('nasabah_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('no_rekening')->unique(); // e.g. BS-2026-001
            $table->decimal('balance', 15, 2)->default(0);
            $table->integer('points')->default(0);
            $table->decimal('co2_saved_kg', 10, 2)->default(0);
            $table->text('address')->nullable();
            $table->string('rt_rw')->nullable();
            $table->string('qr_code_token')->unique()->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nasabah_profiles');
    }
};
