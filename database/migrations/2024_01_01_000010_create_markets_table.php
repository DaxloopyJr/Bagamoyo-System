<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('markets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->foreignId('region_id')->nullable()->constrained('regions');
            $table->foreignId('district_id')->nullable()->constrained('districts');
            $table->foreignId('ward_id')->nullable()->constrained('wards');
            $table->foreignId('village_id')->nullable()->constrained('villages');
            $table->string('street')->nullable();
            $table->integer('total_cages')->default(0);
            $table->integer('occupied_cages')->default(0);
            $table->string('market_type')->default('retail');
            $table->text('facilities')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('markets');
    }
};
