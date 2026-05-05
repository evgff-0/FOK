<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index() { return Staff::latest()->get(); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string', 
            'image' => 'nullable|image|max:2048',
            'category' => 'required|string'
        ]);
    
        $photoPath = null;
        if ($request->hasFile('image')) {
            $photoPath = $request->file('image')->store('staff', 'public');
        }
    
        return Staff::create([
            'name' => $data['title'],
            'position' => $data['content'],
            'photo' => $photoPath,
            'category' => $data['category']
        ]);
    }
    public function destroy($id)
    {
        Staff::destroy($id);
        return response()->json(['message' => 'Удалено']);
    }
}