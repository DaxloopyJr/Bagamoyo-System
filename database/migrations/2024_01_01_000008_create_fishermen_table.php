<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fishermen', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('id_number')->nullable();
            $table->date('registration_date')->default(now());
            $table->foreignId('region_id')->nullable()->constrained('regions');
            $table->foreignId('district_id')->nullable()->constrained('districts');
            $table->foreignId('ward_id')->nullable()->constrained('wards');
            $table->foreignId('village_id')->nullable()->constrained('villages');
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fishermen');
    }
};
