<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;

class TaskController extends Controller
{
    /**
     * Display tasks list page
     *
     * @return \Illuminate\View\View
     */
    public function tasks()
    {
        $tasks = Task::with('user', 'role')->get();

        return view('tasks.tasks', ['tasks' => $tasks]);
    }

    /**
     * Display add task page
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $user = auth()->user();
        $options = $user->getRolesAsOptions();

        return view('tasks.task_form', ['task_types' => $options]);
    }

    /**
     * Display edit task page
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request)
    {
        $user = auth()->user();
        $options = $user->getRolesAsOptions();

        $task = Task::find($request->get('task'));
        return view('tasks.task_form', ['task' => $task, 'task_types' => $options]);
    }

    public function add(TaskRequest $request)
    {
        try {
            $task = new Task();

            $task->name = $request->get('name');
            $task->description = $request->get('description');
            $task->points = $request->get('points');
            $task->created_by = auth()->user()->getAuthIdentifier();
            $task->type = $request->get('type');
            $task->date_from = $request->get('date_from');
            $task->date_to = $request->get('date_to');
            $task->active = false;

            if (!$task->save())
            {
                throw new \Exception('Add failed.');
            }

            return redirect()->route('task.tasks')->withStatus(__('Task successfully added.'));
        }
        catch (\Exception $e) {
            return back()->withError(__('Task add failed.'));
        }
    }

    public function update(TaskRequest $request)
    {
        try {
            $task = Task::find($request->get('task'));
            if (!$task->update($request->all()))
            {
                throw new \Exception('Update failed.');
            }
            return redirect()->route('task.tasks')->withStatus(__('Task successfully updated.'));
        }
        catch (\Exception $e) {
            return back()->withError(__('Task update failed.'));
        }
    }

    public function remove(Request $request)
    {
        try {
            $task = Task::find($request->get('id_task'));
            if (!$task->destroy($task->id))
            {
                throw new \Exception('Remove failed.');
            }
            return back()->withStatus(__('Task successfully removed.'));
        }
        catch (\Exception $e) {
            return back()->withError(__('Task remove failed.'));
        }
    }
}
