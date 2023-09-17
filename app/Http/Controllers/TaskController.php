<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function add(TaskRequest $request)
    {
        $task = new Task();

        $task->name = $request->get('name');
        $task->description = $request->get('description');
        $task->points = $request->get('points');
        $task->created_by = auth()->user()->getAuthIdentifier();

        $task->save();

        return back()->withStatus(__('Task successfully added.'));
    }
}
