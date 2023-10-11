<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Freshman;
use App\Models\UserPoint;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display tasks list page
     *
     * @return \Illuminate\View\View
     */
    public function tasks()
    {
        $tasks = Task::getTasks(true);
        $quests = Task::getQuests(true);

        $freshmen = Freshman::getFreshmen();

        return view('tasks.tasks', ['tasks' => $tasks, 'quests' => $quests, 'freshmen' => $freshmen]);
    }

    /**
     * Display add task page
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        $options = $user->getRolesAsOptions();
        $selected_type = $request->get('selected_type');

        return view('tasks.task_form', ['task_types' => $options, 'selected_type' => $selected_type]);
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

    public function assign(Request $request)
    {
        try {
            $task = Task::find($request->get('task'));

            foreach ($request->get('freshman') as $freshman_id)
            {
                $user_point = new UserPoint();
                $user_point->id_task = $task->id;
                $user_point->id_user = $freshman_id;
                $user_point->assigned_at = date('Y-m-d H:m:s');

                if (!$user_point->save()) {
                    throw new \Exception('Assign failed.');
                }
            }
            if ($task->type == config('custom.QUEST_ID'))
            {
                return redirect()->route('task.tasks')->withQuestStatus(__('Quest successfully assigned.'));
            }
            else
            {
                return redirect()->route('task.tasks')->withStatus(__('Task successfully assigned.'));
            }
        }
        catch (\Exception $e) {
            return back()->withError(__('Task assignation failed.'));
        }
    }

    public function unassign(Request $request)
    {
        try {
            $id = $request->get('id_user_point');

            if(!UserPoint::find($id)->delete())
            {
                throw new \Exception('Unassignation failed.');
            }

            return back()->withStatus(__('Task successfully unassigned.'));
        }
        catch (\Exception $e) {
            return back()->withError(__('Task unassign failed.'));
        }
    }

    public function verify(Request $request)
    {
        try {
            $id = $request->get('id_user_point');
            $user_point = UserPoint::find($id);

            if (!empty($user_point->verified_at))
            {
                return back()->withStatus(__('Task was already verified.'));
            }

            $date_now = date('Y-m-d H:m:s');
            if (empty($user_point->finished_at))
            {
                $user_point->finished_at = $date_now;
            }
            $user_point->verified_at = $date_now;

            if(!$user_point->save())
            {
                throw new \Exception('Verification failed.');
            }

            return back()->withStatus(__('Task successfully verified.'));
        }
        catch (\Exception $e) {
            return back()->withError(__('Task verification failed.'));
        }
    }
}
