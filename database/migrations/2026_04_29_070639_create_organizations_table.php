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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('org_code', 20)->unique();
            $table->string('name', 150)->nullable();
            $table->string('email', 150)->nullable()->unique();
            $table->string('phone', 50)->nullable();
            $table->string('reg_number', 100)->nullable();
            $table->string('tax_number', 100)->nullable();
            $table->string('street_address')->nullable();
            $table->string('suburb', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('country', 10)->default('ZA');
            $table->string('currency', 10)->default('ZAR');
            $table->string('logo_filename', 100)->nullable();
            $table->enum('status', ['active', 'suspended', 'deleted'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
