<?php

namespace App\Http\Controllers;

use App\Services\PermissionService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct(protected PermissionService $permissionService)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $permissions = $this->permissionService->getPermission();
        return view('permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        $this->permissionService->storePermission($request->only('name'));

        return redirect()->route('permissions.index')->with('message', 'Permission created successfully')->with('type','success');
    }

    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
        ]);

        $this->permissionService->editPermission($permission, $request->only('name'));

        return redirect()
            ->route('permissions.index')
            ->with('message', 'Permission updated successfully')->with('type','success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        try {
            $this->permissionService->deletePermission($permission);

            return back()->with('message', 'Permission deleted successfully')
                         ->with('type', 'success');
        } catch (\Exception $e) {
            return back()->with('message', $e->getMessage())
                         ->with('type', 'warning');
        }
    }
}
