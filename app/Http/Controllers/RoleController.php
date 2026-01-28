<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Services\RoleService;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(protected RoleService $roleService) {
        
    }
    public function index()
    {
        $roles = $this->roleService->getAll();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = $this->roleService->getAllPermission();
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        $this->roleService->create($data);

        return redirect()->route('roles.index')
            ->with('message','Role created successfully')
            ->with('type','success');
    }
   
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('roles.edit', [
            'role' => $role,
            'permissions' => $this->roleService->getAllPermission(),
            'rolePermissions' => $this->roleService->getRolePermissions($role)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        $this->roleService->update($role, $data);

        return redirect()->route('roles.index')
            ->with('message','Role updated successfully')
            ->with('type','success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try {
            $this->roleService->delete($role);
            return back()->with('message','Role deleted')->with('type','success');
        } catch (\Exception $e) {
            return back()->with('message',$e->getMessage())->with('type','warning');
        }
    }

    
}
