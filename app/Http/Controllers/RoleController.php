<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::get();
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('message','Role created successfully.')->with('type','success');
    }
   
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {   
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('roles.edit', compact('permissions', 'role', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        $role->update([
            'name' => $request->name
        ]);
        $role->syncPermissions($request->permissions ?? []);
        return redirect()
            ->route('roles.index')
            ->with('message', 'Role updated successfully')->with('type','success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if($role->name=='admin'){
            return back()->with('message', 'Cannot delete Admin role')->with('type','warning');
        }
        if($role->users()->count()>0){
            return back()->with('message', 'Cannot delete role assigned to users')->with('type','warning');
        }
        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with('message', 'Role deleted successfully')->with('type','success');
    }

    
}
