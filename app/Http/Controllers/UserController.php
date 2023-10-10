<?php

namespace App\Http\Controllers;

use App\Models\Freshman;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
            $user = new User();

            $user->name = $request->get('name');
            $user->email = $request->get('email');

            $password =  Str::random(12);
            $user->password = $password;

            if (!$user->save())
            {
                throw new \Exception('User add failed.');
            }

            $roles = $request->get('roles');
            if (!$user->updateRoles($roles))
            {
                throw new \Exception('Update add failed.');
            }
            return redirect()->route('users.index')->withStatus(__('User successfully added. Password: ' . $password));
        }
        catch (\Exception $e) {
            return back()->withError(__('User add failed.'));
        }
    }

    public function remove(Request $request)
    {
        try {
            $user = User::find($request->get('id_user'));
            if (!$user->destroy($user->id))
            {
                throw new \Exception('User remove failed.');
            }
            return redirect()->route('users.index')->withStatus(__('User successfully removed.'));
        }
        catch (\Exception $e) {
            return back()->withError(__($e->getMessage()));
        }
    }
}
