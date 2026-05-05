<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    public function index()
    {
        return Schedule::latest()->get();
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'time' => 'required',
        'activity' => 'required',
        'trainer' => 'required',
        'day_of_week' => 'nullable|string' 
    ]);

    if (!isset($data['day_of_week'])) {
        $data['day_of_week'] = 'Понедельник';
    }

    return Schedule::create($data);
}
    public function book(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        
        $existing = $request->user()->bookings()
            ->where('schedule_id', $schedule->id)
            ->first();
        
        if ($existing) {
            return response()->json([
                'message' => 'Вы уже записаны на это занятие'
            ], 409);
        }
        
        $booking = $request->user()->bookings()->create([
            'schedule_id' => $schedule->id,
        ]);
        
        $booking->load('schedule');
        
        return response()->json([
            'message' => 'Вы успешно записаны на занятие',
            'booking' => $booking
        ], 201);
    }

    public function destroy($id)
    {
        Schedule::destroy($id);
        return response()->json(['message' => 'Удалено']);
    }
}