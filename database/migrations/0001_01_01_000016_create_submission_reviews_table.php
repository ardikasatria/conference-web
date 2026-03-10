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
        Schema::create('submission_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('submissions')->onDelete('cascade');
            $table->foreignId('reviewed_by')->constrained('users')->onDelete('cascade'); // Admin reviewer
            $table->foreignId('conference_id')->constrained('conferences')->onDelete('cascade');
            
            // Review details
            $table->enum('status', ['pending', 'approved', 'rejected', 'revision_requested'])->default('pending');
            $table->text('comments')->nullable();
            $table->integer('rating')->nullable(); // 1-5 rating
            
            // Revision request
            $table->text('revision_notes')->nullable();
            $table->boolean('requires_revision')->default(false);
            
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_reviews');
    }
};
