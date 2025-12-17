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
        Schema::table('applicants', function (Blueprint $table) {
            $table->enum('trustee_status', ['pending', 'requested', 'approved', 'rejected'])->default('pending')->after('trustee_email');
            $table->timestamp('trustee_responded_at')->nullable()->after('trustee_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn(['trustee_status', 'trustee_responded_at']);
        });
    }
};
