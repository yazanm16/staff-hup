<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RoleService;
use App\Http\Resources\RoleResource;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
class RoleController{
    use ApiResponse;
    public function __construct(protected RoleService $roleService)
    {

    }
     public function index()
    {
        return $this->success(
            ['Roles'=> RoleResource::collection($this->roleService->getAll()),]
        );
    }

    public function store(Request $request)
    {
        $role = $this->roleService->create($request->all());
        return $this->success(['Roles'=> new RoleResource($role)], 'Role created', 201);
    }
    public function update(Request $request, Role $role)
    {
        $role = $this->roleService->update($role, $request->all());
        return $this->success(['roles'=>new RoleResource($role)], 'Role updated');
    }

    public function destroy(Role $role)
    {
        try {
            $this->roleService->delete($role);
            return $this->success([], 'Role deleted');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
}