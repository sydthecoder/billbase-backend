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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->restrictOnDelete();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->string('quote_number', 50);
            $table->string('title', 255)->nullable();
            $table->enum('status', [
                'draft', 'sent', 'viewed', 'accepted', 'declined', 'expired', 'converted'
            ])->default('draft');
            $table->date('issue_date');
            $table->date('expires_at');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('tax_total', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->text('footer')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('converted_at')->nullable();
            // TODO: FK constraint added in invoices migration
            // $table->foreign('converted_to_invoice_id')->references('id')->on('invoices');
            $table->unsignedBigInteger('converted_to_invoice_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // quote_number unique per org
            $table->unique(['organization_id', 'quote_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
