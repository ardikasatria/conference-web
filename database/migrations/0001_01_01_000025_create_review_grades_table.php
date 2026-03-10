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
        Schema::create('review_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paper_review_id')->constrained('paper_reviews')->onDelete('cascade');
            $table->foreignId('grading_criteria_id')->constrained('grading_criteria')->onDelete('cascade');
            $table->decimal('score', 5, 2);
            $table->text('notes')->nullable(); // Alasan/catatan untuk score ini
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_grades');
    }
};
