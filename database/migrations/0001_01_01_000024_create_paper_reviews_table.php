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
        Schema::create('paper_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('submissions')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade'); // Reviewer (user dengan reviewer role)
            $table->foreignId('conference_id')->constrained('conferences')->onDelete('cascade');
            
            // Review details
            $table->dateTime('review_date')->nullable();
            $table->decimal('total_score', 5, 2)->nullable(); // Total score dari semua criteria
            $table->enum('status', ['pending', 'in_progress', 'completed', 'accepted', 'rejected'])->default('pending');
            
            // Comments & recommendation
            $table->longText('comments')->nullable();
            $table->text('recommendation')->nullable(); // Recommendation untuk accept/reject
            $table->boolean('recommend_accept')->nullable(); // True = accept, False = reject, Null = undecided
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paper_reviews');
    }
};
