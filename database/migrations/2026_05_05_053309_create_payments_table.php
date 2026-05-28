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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->enum('payment_method', [
                'eft', 'paystack', 'payfast', 'ozow', 'cash', 'other'
            ])->default('eft');
            $table->string('gateway', 50)->nullable();
            $table->string('gateway_reference', 150)->nullable();
            $table->string('gateway_status', 50)->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('ZAR');
            $table->enum('status', [
                'pending', 'completed', 'failed', 'refunded'
            ])->default('completed');
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            // GATEWAY TOKENS — STUBBED FOR PHASE 3
            // $table->string('paystack_token')->nullable();
            // $table->string('payfast_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
