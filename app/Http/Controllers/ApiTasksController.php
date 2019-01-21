<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class ApiTasksController extends Controller
{
    public function index()
    {
        return Task::all();
    }

    public function show($id)
    {
        return response()->json(Task::find($id), 200);
    }

    public function store(Request $request)
    {

        $task = Task::create($request->all());
        return response()->json($task, 201);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        if (!$task) {
            return response()->json(array(
                'error' => array(
                    'message' => 'Bad request. The standard option for requests that fail to pass validation.'
                )
            ), 400);
        }
        $task->update($request->all());
        return response()->json($task, 200);
    }

    public function delete(Task $task)
    {
        $task->delete();
        return response()->json(null, 204);
    }
}
