<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_bookings', function (Blueprint $table) {
            $table->string('booking_type', 20)
                ->default('functional')
                ->after('status');
            $table->index(['occurrence_id', 'booking_type'], 'course_bookings_occurrence_booking_type_idx');
        });

        DB::table('course_bookings')
            ->whereNull('booking_type')
            ->update(['booking_type' => 'functional']);
    }

    public function down(): void
    {
        Schema::table('course_bookings', function (Blueprint $table) {
            $table->dropIndex('course_bookings_occurrence_booking_type_idx');
            $table->dropColumn('booking_type');
        });
    }
};

