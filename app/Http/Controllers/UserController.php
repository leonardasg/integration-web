<?php

namespace App\Http\Controllers;

use App\Models\Role;
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
        $users = $model->paginate(15);

        foreach ($users as $user)
        {
            $roles_name = $user->getRolesName();
            $user->roles = !empty($roles_name) ? $roles_name : '-';
        }

        return view('users.index', ['users' => $users]);
    }

    /**
     * Display a form of user edition
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\View\View
     */
    public function edit(Request $request)
    {
        $user = User::find($request->get('user'));
        return view('users.user_form', ['user' => $user, 'roles' => Role::all()]);
    }

    /**
     * Display a form of user add
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('users.user_form', ['roles' => Role::all()]);
    }

    public function update(Request $request)
    {
        try {
            $user = User::find($request->get('user'));
            if (!$user->update($request->all()))
            {
                throw new \Exception('User update failed.');
            }
            if (!$user->updateRoles($request->get('roles')))
            {
                throw new \Exception('User roles update failed.');
            }
            return redirect()->route('users.index')->withStatus(__('User successfully updated.'));
        }
        catch (\Exception $e) {
            return back()->withError(__($e->getMessage()));
        }
    }

    public function add(Request $request)
    {
        try {
            $user = User::find($request->get('user'));
            if (!$user->update($request->all()))
            {
                throw new \Exception('Update failed.');
            }
            return redirect()->route('users.index')->withStatus(__('User successfully updated.'));
        }
        catch (\Exception $e) {
            return back()->withError(__('User update failed.'));
        }
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
