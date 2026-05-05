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
public function book(Request $request) {
    return response()->json(['message' => 'Записались!']);
}

    public function destroy($id)
    {
        Schedule::destroy($id);
        return response()->json(['message' => 'Удалено']);
    }
}