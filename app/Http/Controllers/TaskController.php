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
        $tasks_by_type = Task::getTasks(true, true);
        $quests = Task::getQuests(true);

        $freshmen = Freshman::getFreshmen();

        return view('tasks.tasks', ['tasks' => $tasks, 'tasks_by_type' => $tasks_by_type, 'quests' => $quests, 'freshmen' => $freshmen]);
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

        return view('tasks.task_form', [
            'task_types' => $options,
            'selected_type' => $selected_type
        ]);
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
            $task->active = $request->get('active') ?? false;

            if (!$task->save())
            {
                throw new \Exception('Add failed.');
            }

            return redirect()->back()->withStatus(__('Task successfully added.'));
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
            return redirect()->back()->withStatus(__('Task successfully updated.'));
        }
        catch (\Exception $e) {
            return back()->withError(__('Task update failed.'));
        }
    }

    public function remove(Request $request)
    {
        try {
            $task = Task::find($request->get('id_task'));

            $this->assign($request, $task);

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

    public function assign(Request $request, $task = null)
    {
        try {
            if (!isset($task))
            {
                $task = Task::find($request->get('task'));
            }

            if (!auth()->user()->canEditTask($task))
            {
                return back()->withError(__('You do not have permissions to verify this task.'));
            }

            $freshmen = $request->get('freshman');
            if (!empty($freshmen) && $freshmen[0] == 'all')
            {
                array_shift($freshmen);
            }

            $assigned_before = UserPoint::where('id_task', $task->id)->get();
            $return = $this->unassignUnchecked($task, $assigned_before, $freshmen);
            if (!empty($return))
            {
                return $return;
            }

            foreach ($freshmen as $freshman_id)
            {
                // skip if already assigned
                $already_assigned = UserPoint::get()->where('id_user', $freshman_id)->where('id_task', $task->id)->all();
                if (!empty($already_assigned))
                {
                    continue;
                }

                // assign new freshman
                $user_point = new UserPoint();
                $user_point->id_task = $task->id;
                $user_point->id_user = $freshman_id;
                $user_point->assigned_at = date('Y-m-d H:m:s');

                if (!$user_point->save())
                {
                    throw new \Exception('Assign failed.');
                }
            }

            if ($task->type == config('custom.QUEST_ID'))
            {
                return redirect()->back()->withQuestStatus(__('Quest successfully assigned.'));
            }
            else
            {
                return redirect()->back()->withStatus(__('Task successfully assigned.'));
            }
        }
        catch (\Exception $e) {
            return back()->withError(__('Task assignation failed.'));
        }
    }

    public function unassignUnchecked($task, $assigned_before, $assigned_new)
    {
        // remove all
        if (empty($assigned_new))
        {
            $assigned_before->each(function ($user_point)
            {
                $user_point->delete();
            });

            if ($task->type == config('custom.QUEST_ID'))
            {
                return redirect()->back()->withQuestStatus(__('Quest successfully unassigned.'));
            }
            else
            {
                return redirect()->back()->withStatus(__('Task successfully unassigned.'));
            }
        }

        // remove who are unchecked
        $assigned_before->each(function ($user_point) use ($assigned_new)
        {
            if (!in_array($user_point->id_user, $assigned_new))
            {
                $user_point->delete();
            }
        });

        return false;
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

            $task = Task::find($user_point->id_task);
            if (!auth()->user()->canEditTask($task))
            {
                return back()->withError(__('You do not have permissions to verify this task.'));
            }

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

    public function unverifyUnchecked($task, $verified_before, $verified_new)
    {
        // unverify all
        if (empty($verified_new))
        {
            $verified_before->each(function ($user_point)
            {
                $user_point->verified_at = null;
                $user_point->save();
            });

            if ($task->type == config('custom.QUEST_ID'))
            {
                return redirect()->back()->withQuestStatus(__('Quest successfully unverified.'));
            }
            else
            {
                return redirect()->back()->withStatus(__('Task successfully unverified.'));
            }
        }

        // unverify who are unchecked
        $verified_before->each(function ($user_point) use ($verified_new)
        {
            if (!in_array($user_point->id_user, $verified_new))
            {
                $user_point->verified_at = null;
                $user_point->save();
            }
        });

        return false;
    }

    public function bulkVerify(Request $request, $task = null)
    {
        try {
            if (!isset($task))
            {
                $task = Task::find($request->get('task'));
            }

            if (!auth()->user()->canEditTask($task))
            {
                return back()->withError(__('You do not have permissions to verify this task.'));
            }

            $freshmen = $request->get('freshman');
            if (!empty($freshmen) && $freshmen[0] == 'all')
            {
                array_shift($freshmen);
            }
            if (!empty($freshmen) && $freshmen[0] == 'all-finished')
            {
                array_shift($freshmen);
            }

            $verified_before = UserPoint::where('id_task', $task->id)->whereNotNull('verified_at')->get();
            $return = $this->unverifyUnchecked($task, $verified_before, $freshmen);
            if (!empty($return))
            {
                return $return;
            }

            foreach ($freshmen as $freshman_id)
            {
                // skip if already verified
                $user_point = UserPoint::get()->where('id_user', $freshman_id)->where('id_task', $task->id)->first();
                if (!empty($user_point->verified_at))
                {
                    continue;
                }

                $date_now = date('Y-m-d H:m:s');
                if (empty($user_point->finished_at))
                {
                    $user_point->finished_at = $date_now;
                }
                $user_point->verified_at = $date_now;

                if (!$user_point->save())
                {
                    throw new \Exception('Verify failed.');
                }
            }

            if ($task->type == config('custom.QUEST_ID'))
            {
                return redirect()->back()->withQuestStatus(__('Quest successfully verified.'));
            }
            else
            {
                return redirect()->back()->withStatus(__('Task successfully verified.'));
            }
        }
        catch (\Exception $e) {
            return back()->withError(__('Task verification failed.'));
        }
    }

    public function getAssignedFreshmen()
    {
        $id_task = request('id_task');
        $task = Task::find($id_task);
        $assigned = $task->getAssigned();
        $freshmen = Freshman::getFreshmen();

        if (empty($assigned))
        {
            return response()->json(['message' => 'No assigned'], 404);
        }
        $response = [];
        $response['assigned'] = $assigned;

        $response['all'] = false;
        if (count($assigned) == count($freshmen))
        {
            $response['all'] = true;
        }

        return response()->json($response);
    }

    public function getFinishedFreshmen($id_task = null, $json = true)
    {
        if (empty($id_task))
        {
            $id_task = request('id_task');
        }
        $task = Task::find($id_task);

        $finished = $task->getFinished();
        $freshmen = Freshman::getFreshmen();

        if ($json && empty($finished))
        {
            return response()->json(['message' => 'No finished'], 404);
        }

        $finished_formatted = $this->getFormattedArrayByFreshmanId($freshmen, $finished);

        $response = [];
        $response['finished'] = $finished_formatted;
        $response['all'] = false;
        if (count($finished_formatted) == count($freshmen))
        {
            $response['all'] = true;
        }

        return $json ? response()->json($response) : $response;
    }

    public function getVerifiedFreshmen()
    {
        $id_task = request('id_task');
        $task = Task::find($id_task);

        $verified = $task->getVerified();
        $finished = $task->getFinished();
        $freshmen = Freshman::getFreshmen();

        $response = [];

        $response['verified'] = $verified;
        $response['finished'] = $finished;
        $response['all_verified'] = false;
        if (count($verified) == count($freshmen))
        {
            $response['all_verified'] = true;
        }
        $response['all_finished'] = false;
        if (count($finished) == count($verified))
        {
            $response['all_finished'] = true;
        }

        return response()->json($response);
    }

    public function getFormattedArrayByFreshmanId($freshmen, $array)
    {
        $finished_formatted = [];
        foreach ($freshmen as $freshman)
        {
            foreach ($array as $item)
            {
                if ($freshman->user->id == $item->id_user)
                {
                    $finished_formatted[$freshman->user->id] = $item;
                }
            }
        }
        return $finished_formatted;
    }
}
