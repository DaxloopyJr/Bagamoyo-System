<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_licenses', function (Blueprint $table) {
            $table->id();
            $table->string('owner_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('license_number')->unique();
            $table->foreignId('license_category_id')->constrained('license_categories');
            $table->enum('license_type', ['mid_year', 'annual']);
            $table->date('issue_date');
            $table->date('expiry_date');
            $table->decimal('payment_amount', 12, 2)->default(0);
            $table->enum('payment_status', ['issue_payment', 'renewal_payment', 'not_paid'])->default('not_paid');
            $table->string('business_name');
            $table->text('business_description')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->foreignId('region_id')->nullable()->constrained('regions');
            $table->foreignId('district_id')->nullable()->constrained('districts');
            $table->foreignId('ward_id')->nullable()->constrained('wards');
            $table->foreignId('village_id')->nullable()->constrained('villages');
            $table->string('street')->nullable();
            $table->string('building')->nullable();
            $table->boolean('hygiene_reminder_sent')->default(false);
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['expiry_date', 'payment_status']);
            $table->index(['license_type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_licenses');
    }
};
