<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ToDoController extends Controller
{
    public function index()
{
    $todos = ToDo::all();
    return response()->json($todos);
}

public function store(Request $request)
{
    $todo = ToDo::create($request->only(['title']));
    return response()->json($todo, 201);
}

public function update(Request $request, ToDo $todo)
{
    $todo->update($request->only(['title', 'completed']));
    return response()->json($todo);
}

public function destroy(ToDo $todo)
{
    $todo->delete();
    return response()->json(null, 204);
}
}
