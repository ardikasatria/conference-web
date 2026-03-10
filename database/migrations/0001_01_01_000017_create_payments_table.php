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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations')->onDelete('cascade');
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('conference_id')->constrained('conferences')->onDelete('cascade');
            
            // Payment details
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'awaiting_confirmation', 'confirmed', 'paid', 'cancelled'])->default('pending');
            
            // Bank transfer details (shown to user)
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable(); // Rekening tujuan
            $table->string('account_holder')->nullable();
            
            // Payment proof
            $table->string('payment_invoice_number')->nullable(); // Invoice number untuk tracking
            $table->timestamp('due_date')->nullable();
            
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
