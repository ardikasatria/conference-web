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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('conference_id')->constrained('conferences')->onDelete('cascade');
            
            // Abstract information
            $table->string('title');
            $table->longText('abstract');
            $table->json('keywords')->nullable();
            
            // Presenter information
            $table->string('presenter_name');
            $table->string('presenter_email');
            
            // Co-authors
            $table->json('co_authors')->nullable();
            
            // Topic/kategori
            $table->string('topic')->nullable();
            $table->json('subtopics')->nullable();
            
            // File submission
            $table->string('file_path')->nullable(); // Path ke file abstract PDF/DOC
            
            // Status
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->text('submission_notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
