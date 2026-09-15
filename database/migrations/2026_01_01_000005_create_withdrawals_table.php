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
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g. WD-20260914-0001
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 15, 2)->default(0); // nominal penarikan rupiah
            $table->integer('points_used')->default(0); // poin reward yang ditukar jika klaim hadiah
            $table->string('type')->default('ewallet'); // bank_transfer, ewallet, cash, reward_goods
            $table->string('channel'); // GoPay, OVO, DANA, BCA, Mandiri, Sembako
            $table->string('account_number')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->string('proof_file')->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
