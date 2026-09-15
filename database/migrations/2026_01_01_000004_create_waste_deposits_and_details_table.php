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
        Schema::create('waste_deposits', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g. DEP-20260914-0001
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Nasabah
            $table->foreignId('officer_id')->nullable()->constrained('users')->nullOnDelete(); // Petugas Penimbang
            $table->string('deposit_type')->default('drop_off'); // drop_off, pickup
            $table->string('status')->default('completed'); // pending, confirmed, completed, cancelled
            $table->text('pickup_address')->nullable();
            $table->date('pickup_date')->nullable();
            $table->string('pickup_time_slot')->nullable();
            $table->decimal('total_weight_kg', 10, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->integer('total_points')->default(0);
            $table->decimal('total_co2_kg', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('waste_deposit_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('waste_deposit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('waste_item_id')->constrained()->cascadeOnDelete();
            $table->decimal('weight', 10, 2); // bobot timbangan (kg atau liter)
            $table->decimal('price_per_unit', 12, 2);
            $table->integer('points_per_unit')->default(0);
            $table->decimal('subtotal_amount', 15, 2);
            $table->integer('subtotal_points')->default(0);
            $table->decimal('subtotal_co2', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waste_deposit_details');
        Schema::dropIfExists('waste_deposits');
    }
};
