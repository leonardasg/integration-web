<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\View\View
     */
    public function index(User $model)
    {
        return view('users.index', ['users' => $model->paginate(15)]);
    }

    public function calculatePointsForUser()
    {
        $id_user = request('id_user');
        $user = User::find($id_user);

        if (!$user)
        {
            return response()->json(['message' => 'User not found'], 404);
        }

        $results = DB::select('SELECT t.points FROM `user_points` up INNER JOIN `tasks` t ON t.`id` = up.`id_task` WHERE up.`id_user` = ?', [$id_user]);
        $points = 0;
        foreach ($results as $row)
        {
            $points += $row->points;
        }

        $points_cp = $points;
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
            'total_points' => $points,
            'points_by_level' => $by_level
        ]);
    }
}
