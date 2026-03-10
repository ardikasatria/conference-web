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
        Schema::create('reviewer_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('conference_id')->constrained('conferences')->onDelete('cascade');
            $table->text('motivation')->nullable(); // Alasan ingin jadi reviewer
            $table->text('expertise')->nullable(); // Keahlian/bidang
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable(); // Catatan dari admin
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null'); // User/admin yang review
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Seorang user hanya bisa apply sekali untuk conference yang sama
            $table->unique(['user_id', 'conference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviewer_applications');
    }
};
