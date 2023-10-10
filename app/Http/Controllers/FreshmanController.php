<?php

namespace App\Http\Controllers;

use App\Models\Freshman;
use App\Models\Level;
use App\Models\User;
use App\Models\UserPoint;
use Illuminate\Http\Request;

class FreshmanController extends Controller
{
    public function freshmen()
    {
        $freshmen = Freshman::getFreshmen();

        return view('freshman.freshmen', ['freshmen' => $freshmen]);
    }

    public function freshman_tasks(Request $request)
    {
        $user = User::find($request->get('id_user'));
        $freshman = new Freshman($user);

        $tasks = $freshman->getTasks(false);
        $quests = $freshman->getQuests(false);
        $freshmen = Freshman::getFreshmen();

        return view('tasks.freshman_tasks', ['tasks' => $tasks, 'freshman' => $freshman, 'quests' => $quests, 'freshmen' => $freshmen]);
    }

    public function getPointsasForDisplay()
    {
        $id_user = request('id_user');
        $user = User::find($id_user);
        $freshman = new Freshman($user);

        if (!$freshman)
        {
            return response()->json(['message' => 'Freshman not found'], 404);
        }

        $points_cp = $freshman->points;
        $by_level = [];
        $points_to_next_level = config('custom.POINTS_TO_NEXT_LEVEL');
        while ($points_cp > $points_to_next_level)
        {
            $by_level[] = $points_to_next_level;
            $points_cp -= $points_to_next_level;
        }
        $by_level[] = $points_cp;

        $level_colors = config('custom.LEVEL_COLORS');
        return response()->json([
            'level_colors' => $level_colors,
            'to_next_level' => $points_to_next_level,
            'total_points' => $freshman->points,
            'points_by_level' => $by_level
        ]);
    }

    public function getPointsForDisplay()
    {
        $id_user = request('id_user');
        $user = User::find($id_user);
        $freshman = new Freshman($user);

        if (!$freshman)
        {
            return response()->json(['message' => 'Freshman not found'], 404);
        }

        $levels = Level::all();
        $left_points = $freshman->points - $levels[$freshman->level - 1]->points;

        return response()->json([
            'all_levels' => Level::all(),
            'left_points' => $left_points,
            'level' => $freshman->level,
        ]);
    }

    public function finishTask()
    {
        $id_user_point = request('id_user_point');
        $user_point = UserPoint::find($id_user_point);

        if (request('finished'))
        {
            $user_point->finished_at = date('Y-m-d H:m:s');
        }
        else
        {
            $user_point->finished_at = null;
        }

        if ($user_point->save())
        {
            return response()->json([
                'result' => 'success'
            ]);
        }

        return response()->json([
            'result' => 'failed'
        ]);
    }
}
