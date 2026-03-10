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
        Schema::table('reviewer_applications', function (Blueprint $table) {
            $table->string('field_of_study')->nullable()->after('expertise'); // Bidang ilmu
            $table->string('sub_field')->nullable()->after('field_of_study'); // Sub bidang keahlian
            $table->json('selected_topics')->nullable()->after('sub_field'); // Topik pilihan dari conference
            $table->string('full_name_with_degree')->nullable()->after('selected_topics'); // Nama lengkap + gelar
            $table->string('affiliation')->nullable()->after('full_name_with_degree'); // Afiliasi/instituasi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviewer_applications', function (Blueprint $table) {
            $table->dropColumn(['field_of_study', 'sub_field', 'selected_topics', 'full_name_with_degree', 'affiliation']);
        });
    }
};
