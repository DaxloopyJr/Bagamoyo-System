<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fishing_boats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fisherman_id')->constrained('fishermen')->onDelete('cascade');
            $table->string('owner_name');
            $table->string('boat_number')->unique();
            $table->decimal('capacity_kg', 10, 2)->default(0);
            $table->decimal('length_m', 8, 2)->nullable();
            $table->string('boat_type')->nullable();
            $table->string('engine_power')->nullable();
            $table->year('year_built')->nullable();
            $table->string('registration_status')->default('registered');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fishing_boats');
    }
};
