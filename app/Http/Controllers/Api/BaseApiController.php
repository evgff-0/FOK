<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

abstract class BaseApiController extends Controller 
{
    protected string $model; 

    public function index() 
    { 
        return $this->model::latest()->get(); 
    }

    public function store(Request $request) 
    {
        return $this->model::create($request->all());
    }

    public function update(Request $request, int|string $id) 
    {
        $item = $this->model::findOrFail($id);
        $item->update($request->all());
        return $item;
    }

    public function destroy(int|string $id) 
    {
        $this->model::destroy($id);
        return response()->json(['message' => 'Успешно удалено'], 200);
    }
}