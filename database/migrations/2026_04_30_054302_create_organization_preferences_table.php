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
        Schema::create('organization_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('invoice_prefix', 20)->nullable();
            $table->unsignedInteger('invoice_starting_number')->nullable();
            $table->unsignedInteger('default_payment_terms')->nullable();
            $table->string('invoice_footer')->nullable();
            $table->text('invoice_notes')->nullable();
            $table->string('quote_prefix', 20)->nullable();
            $table->unsignedInteger('quote_starting_number')->nullable();
            $table->string('customer_code_prefix', 20)->nullable();
            $table->string('brand_color', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_preferences');
    }
};
