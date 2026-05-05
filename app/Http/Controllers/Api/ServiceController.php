<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller {
    public function index() { return Service::latest()->get(); }
    public function store(Request $request) {
        $data = $request->validate(['title' => 'required', 'content' => 'required', 'image' => 'nullable|image']);
        if ($request->hasFile('image')) $data['image'] = $request->file('image')->store('services', 'public');
        return Service::create(['title' => $data['title'], 'description' => $data['content'], 'image' => $data['image'] ?? null]);
    }
    public function destroy($id) { Service::destroy($id); return response()->json(['message' => 'Удалено']); }
}