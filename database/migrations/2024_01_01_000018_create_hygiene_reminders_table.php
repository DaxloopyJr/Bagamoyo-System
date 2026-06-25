<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hygiene_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('license_id')->constrained('business_licenses')->onDelete('cascade');
            $table->text('message');
            $table->enum('status', ['sent', 'delivered', 'failed'])->default('sent');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hygiene_reminders');
    }
};
