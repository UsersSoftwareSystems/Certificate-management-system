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
            $table->text('temple_address')->nullable()->after('educational_details');
            $table->string('trustee_name')->nullable()->after('temple_address');
            $table->string('trustee_mobile')->nullable()->after('trustee_name');
            $table->string('trustee_email')->nullable()->after('trustee_mobile');
            $table->string('trustee_designation')->nullable()->after('trustee_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn([
                'temple_address',
                'trustee_name',
                'trustee_mobile',
                'trustee_email',
                'trustee_designation'
            ]);
        });
    }
};
