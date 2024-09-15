<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ToDo;


class ToDoController extends Controller
{
    public function index()
{
    $todos = ToDo::all();
    // dd($todos);
    return response()->json($todos);

}

public function store(Request $request)
{
    $todo = ToDo::create($request->only(['title']));
    return response()->json($todo, 201);
}

public function update(Request $request, ToDo $todo)
{
    try {
        // Log incoming request data
        \Log::info('Update Request:', $request->all());

        // Update the To-Do item
        $todo->update($request->only(['completed']));

        // Return the updated To-Do item
        
        dd(response()->json($todo));
    } catch (\Exception $e) {
        // Log the full exception
        \Log::error('Update Failed:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        // Return a 500 error response
        return response()->json(['error' => 'Failed to update To-Do status.'], 500);
    }
}

public function destroy(ToDo $todo)
{
    $todo->delete();
    return response()->json(null, 204);
}
}
