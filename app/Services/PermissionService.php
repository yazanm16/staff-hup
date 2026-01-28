<?php
namespace App\Services;

use App\Repositories\Contracts\PermissionRepositoryContract;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    public function __construct(protected PermissionRepositoryContract $permissionRepository)
    {
    }
    public function getPermission(){
        return $this->permissionRepository->all();
    }
    public function storePermission(array $data){
        $data['guard_name'] = 'web';
        return $this->permissionRepository->create($data);
    }
    public function editPermission(Permission $permission,array $data){
        return $this->permissionRepository->edit($permission, $data);
    }
    public function deletePermission(Permission $permission){
        if ($permission->roles()->exists()) {
            throw new \Exception('Permission is assigned to roles');
        }
        $permission->users()->detach();
        return $this->permissionRepository->delete($permission);
    }
}