<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $rows = DB::table('memberships as m')
            ->leftJoin('payments as p', 'p.id', '=', 'm.payment_id')
            ->leftJoin('items as i', 'i.id', '=', 'p.item_id')
            ->whereNotNull('m.start_date')
            ->whereNotNull('m.end_date')
            ->whereColumn('m.end_date', '<=', 'm.start_date')
            ->select([
                'm.id',
                'm.start_date',
                'i.validity_months',
            ])
            ->get();

        foreach ($rows as $row) {
            $start = Carbon::parse($row->start_date)->startOfDay();
            $months = max(1, (int) ($row->validity_months ?? 0));
            $end = $start->copy()->addMonths($months);

            DB::table('memberships')
                ->where('id', $row->id)
                ->update([
                    'end_date' => $end->toDateString(),
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        // Irreversible data fix.
    }
};

