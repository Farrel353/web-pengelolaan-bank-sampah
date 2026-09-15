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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('reference_type'); // 'deposit', 'withdrawal', 'adjustment'
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('type'); // 'credit' (tambah saldo), 'debit' (kurang saldo)
            $table->decimal('amount', 15, 2)->default(0);
            $table->integer('points')->default(0);
            $table->decimal('balance_after', 15, 2)->default(0);
            $table->integer('points_after')->default(0);
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
