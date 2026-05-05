<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller {
    public function index() { 
        return Contact::latest()->get(); 
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',      
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:2000'
        ]);
        
        Contact::create($data);
        return response()->json(['message' => 'Отправлено!']);
    }
}