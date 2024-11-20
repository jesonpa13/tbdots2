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
        Schema::table('applications', function (Blueprint $table) {
            $table->date('ntp_visit_date')->nullable()->after('visit_date'); // Add NTP visit date after the existing visit date field
            $table->text('ntp_remarks')->nullable()->after('ntp_visit_date'); // Add NTP remarks after the NTP visit date field
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('ntp_visit_date');
            $table->dropColumn('ntp_remarks');
        });
    }
};
