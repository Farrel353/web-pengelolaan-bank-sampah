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
        Schema::create('waste_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Plastik, Kertas, Logam, Kaca, Minyak, Elektronik
            $table->string('slug')->unique();
            $table->string('icon')->default('trash');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('waste_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('waste_category_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // e.g. Botol PET Bening, Kardus Lembaran, Kaleng Seng
            $table->string('unit')->default('kg'); // kg, liter, pcs
            $table->decimal('buy_price_per_unit', 12, 2); // Harga beli dari nasabah (Rp)
            $table->integer('points_per_unit')->default(10); // Poin per kg/unit
            $table->decimal('co2_factor_per_unit', 8, 3)->default(1.5); // kg CO2e yang direduksi per kg sampah
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waste_items');
        Schema::dropIfExists('waste_categories');
    }
};
