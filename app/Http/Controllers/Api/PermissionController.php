<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use App\Services\PermissionService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
class PermissionController extends Controller
{
    use ApiResponse;
    public function __construct(protected PermissionService $permissionService)
    {}
    public function index()
    {
        $permissions = $this->permissionService->getPermission();

        return $this->success(
            ['permission'=>PermissionResource::collection($permissions)],
            'Permissions list'
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        $permission = $this->permissionService->storePermission($request->only('name'));

        return $this->success(
            ['permission'=>new PermissionResource($permission)],
            'Permission created',
            201
        );
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
        ]);

        $permission = $this->permissionService->editPermission($permission, $request->only('name'));

        return $this->success(
            ['permission'=>new PermissionResource($permission)],
            'Permission updated'
        );
    }

    public function destroy(Permission $permission)
    {
        try {
            $this->permissionService->deletePermission($permission);

            return $this->success([], 'Permission deleted');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 409);
        }
    }
}
