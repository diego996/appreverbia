<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('course_bookings')
            ->where('status', 'pending_duetto')
            ->update(['status' => 'confirmed_duetto']);
    }

    public function down(): void
    {
        DB::table('course_bookings')
            ->where('status', 'confirmed_duetto')
            ->update(['status' => 'pending_duetto']);
    }
};

