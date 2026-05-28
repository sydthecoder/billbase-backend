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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('quote_id')->nullable();
            $table->string('invoice_number', 50);
            $table->enum('status', [
                'draft', 'sent', 'viewed', 'partial', 'paid', 'overdue', 'cancelled'
            ])->default('draft');
            $table->date('issue_date');
            $table->date('due_date');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('tax_total', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->text('footer')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->boolean('is_locked')->default(false);
            // SARS billing address snapshot
            $table->string('billing_name', 150)->nullable();
            $table->string('billing_company', 150)->nullable();
            $table->string('billing_vat_number', 100)->nullable();
            $table->string('billing_street_address')->nullable();
            $table->string('billing_suburb', 100)->nullable();
            $table->string('billing_city', 100)->nullable();
            $table->string('billing_province', 100)->nullable();
            $table->string('billing_postal_code', 10)->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            // RECURRING — STUBBED FOR PHASE 3
            // $table->boolean('is_recurring')->default(false);
            // $table->json('recurrence_rule')->nullable();
            // $table->unsignedBigInteger('recurrence_parent_id')->nullable();
            // $table->date('next_recurrence_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['organization_id', 'invoice_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
