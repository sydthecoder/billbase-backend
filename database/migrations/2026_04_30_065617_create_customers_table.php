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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('customer_code', 50);
            $table->enum('customer_type', ['individual', 'company']);
            $table->string('company_name', 150)->nullable();
            $table->string('company_reg_number', 100)->nullable();
            $table->string('vat_number', 100)->nullable();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 150);
            $table->string('phone', 50)->nullable();
            $table->string('street_address')->nullable();
            $table->string('suburb', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            // customer_code unique per org
            $table->unique(['organization_id', 'customer_code']);
            // email unique per org
            $table->unique(['organization_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
