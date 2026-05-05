<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->bookings()
            ->with('schedule')
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id', 
        ]);

        $existing = $request->user()->bookings()
            ->where('schedule_id', $validated['schedule_id'])
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Вы уже записаны на это время'], 409);
        }

        return $request->user()->bookings()->create($validated);
    }

    public function destroy(Request $request, $id)
    {
        $request->user()->bookings()->where('id', $id)->delete();
        return response()->json(['message' => 'Запись удалена']);
    }
}