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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conference_id')->constrained('conferences')->onDelete('cascade');
            $table->string('name'); // Silver, Gold, Platinum, dll
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('max_capacity')->nullable(); // Limit peserta per paket
            $table->integer('current_registered')->default(0); // Tracking registered count
            $table->longText('benefits')->nullable(); // JSON atau plain text benefits
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('order')->default(0); // Untuk sorting/display order
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
