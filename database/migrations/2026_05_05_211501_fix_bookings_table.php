<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $bookings = DB::table('bookings')
            ->whereNull('schedule_id')
            ->whereNotNull('activity')
            ->get();
            
        foreach ($bookings as $booking) {
            $schedule = DB::table('schedules')
                ->where('activity', $booking->activity)
                ->where('time', $booking->time)
                ->first();
            
            if ($schedule) {
                DB::table('bookings')
                    ->where('id', $booking->id)
                    ->update(['schedule_id' => $schedule->id]);
            }
        }

        DB::table('bookings')->whereNull('schedule_id')->delete();

        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'activity')) {
                $table->dropColumn('activity');
            }
            if (Schema::hasColumn('bookings', 'time')) {
                $table->dropColumn('time');
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_NAME = 'bookings' 
                AND COLUMN_NAME = 'schedule_id' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            
            if (empty($foreignKeys)) {
                $table->foreign('schedule_id')
                      ->references('id')
                      ->on('schedules')
                      ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['schedule_id']);

            $table->string('activity')->nullable()->after('user_id');
            $table->string('time')->nullable()->after('activity');
        });
    }
};