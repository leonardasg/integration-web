<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Role $model)
    {
        $roles = $model->paginate(15);

        return view('roles.index', ['roles' => $roles]);
    }

    public function edit(Request $request)
    {
        $role = Role::find($request->get('role'));
        return view('roles.role_form', ['role' => $role]);
    }

    public function create()
    {
        return view('roles.role_form');
    }

    public function update(Request $request)
    {
        try {
            $role = Role::find($request->get('role'));
            if (!$role->update($request->all()))
            {
                throw new \Exception('Role update failed.');
            }
            return redirect()->route('role.index')->withStatus(__('Role successfully updated.'));
        }
        catch (\Exception $e) {
            return back()->withError(__($e->getMessage()));
        }
    }

    public function add(Request $request)
    {
        try {
            $role = new Role();

            $role->name = $request->get('name');
            $role->as_type = $request->get('as_type');

            if (!$role->save())
            {
                throw new \Exception('Role add failed.');
            }

            return redirect()->route('role.index')->withStatus(__('Role successfully added.'));
        }
        catch (\Exception $e) {
            return back()->withError(__($e->getMessage()));
        }
    }

    public function remove(Request $request)
    {
        try {
            $role = Role::find($request->get('id_role'));
            if (!$role->destroy($role->id))
            {
                throw new \Exception('Role remove failed.');
            }
            return redirect()->route('role.index')->withStatus(__('Role successfully removed.'));
        }
        catch (\Exception $e) {
            return back()->withError(__($e->getMessage()));
        }
    }
}
