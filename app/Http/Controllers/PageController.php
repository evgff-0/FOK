<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Schedule;
use App\Models\Contact;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home() {
        return view('pages.home');
    }

    public function services() {
        // Берем все услуги из БД
        $services = Service::all();
        return view('pages.services', compact('services'));
    }

    public function schedule() {
        // Берем расписание из БД
        $schedules = Schedule::orderBy('start_time')->get();
        return view('pages.schedule', compact('schedules'));
    }

    public function contacts() {
        return view('pages.contacts');
    }
    public function storeContact(Request $request) 
    {
        $validated = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'message' => 'required',
        ]);

        Contact::create($validated);
    
        return back()->with('success', 'Спасибо! Мы свяжемся с вами.');
    }
}
