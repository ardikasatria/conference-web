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
        Schema::table('registrations', function (Blueprint $table) {
            $table->foreignId('submission_id')->nullable()->constrained('submissions')->onDelete('set null');
            $table->enum('submission_status', ['not_required', 'pending_submission', 'pending_review', 'approved', 'rejected'])->default('pending_submission');
            $table->enum('payment_status', ['not_required', 'pending_payment', 'awaiting_confirmation', 'confirmed', 'paid', 'cancelled'])->default('pending_payment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeignIdFor('submissions');
            $table->dropColumn(['submission_id', 'submission_status', 'payment_status']);
        });
    }
};
