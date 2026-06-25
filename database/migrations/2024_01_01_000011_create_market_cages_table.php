<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('market_cages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('market_id')->constrained('markets')->onDelete('cascade');
            $table->string('cage_number');
            $table->decimal('cost', 12, 2)->default(0);
            $table->decimal('rent_cost', 12, 2)->default(0);
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->string('occupied_by')->nullable();
            $table->date('occupied_date')->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['market_id', 'cage_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('market_cages');
    }
};
