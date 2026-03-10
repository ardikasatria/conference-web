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
        Schema::create('payment_confirmations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            $table->foreignId('registration_id')->constrained('registrations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('conference_id')->constrained('conferences')->onDelete('cascade');
            
            // Confirmation details dari user
            $table->string('bank_name'); // Bank yang ditransfer
            $table->string('sender_name'); // Nama pengirim
            $table->string('transaction_date'); // Tanggal transfer
            $table->string('reference_number')->unique(); // Nomor referensi/bukti transfer
            $table->decimal('amount_transferred', 10, 2); // Jumlah yang ditransfer
            $table->string('proof_image_path')->nullable(); // Bukti transfer (screenshot)
            $table->text('notes')->nullable(); // Catatan dari user
            
            // Review by admin
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null'); // Admin yang verify
            
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_confirmations');
    }
};
