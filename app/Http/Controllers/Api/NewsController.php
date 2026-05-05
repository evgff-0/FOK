<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index() { return News::latest()->get(); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $data['image'] = $path; 
        }

        return News::create($data);
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        if ($news->image) Storage::disk('public')->delete($news->image);
        $news->delete();
        return response()->json(['message' => 'Удалено']);
    }
}