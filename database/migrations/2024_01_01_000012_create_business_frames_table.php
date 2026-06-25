<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_frames', function (Blueprint $table) {
            $table->id();
            $table->string('frame_number');
            $table->string('frame_name')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->foreignId('region_id')->nullable()->constrained('regions');
            $table->foreignId('district_id')->nullable()->constrained('districts');
            $table->foreignId('ward_id')->nullable()->constrained('wards');
            $table->foreignId('village_id')->nullable()->constrained('villages');
            $table->string('street')->nullable();
            $table->string('area_description')->nullable();
            $table->enum('status', ['rented', 'not_rented', 'under_maintenance'])->default('not_rented');
            $table->decimal('rent_cost', 12, 2)->default(0);
            $table->string('rented_to')->nullable();
            $table->string('rented_to_phone')->nullable();
            $table->date('rent_start_date')->nullable();
            $table->date('rent_end_date')->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_frames');
    }
};
