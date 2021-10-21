<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $this->data['roles'] = Role::all();
        return view('admin.role.index', $this->data);
    }

    public function validateRequest($request)
    {
        $this->validate($request, [
            'name' => 'required|max:255'
        ], [
            'name.required' => 'Role Name is required.',
            'name.max' => 'Maximum of 255 characters only.'
        ]);
    }

    public function saveRequest($role, $request)
    {
        $role->name = $request->name;
        $role->save();
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);
        $role = new Role();
        $this->saveRequest($role, $request);
        return redirect()->route('roles.index')->withSuccess('Added Successfully!');
    }

    public function update(Request $request)
    {
        $this->validateRequest($request);
        $this->validate($request, [
            'id'=>'required',
        ]);
        $role = Role::find($request->id);
        $this->saveRequest($role, $request);

        return redirect()->route('roles.index')->withSuccess('Updated Successfully!');
    }
}
