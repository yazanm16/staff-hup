<?php
namespace App\Repositories;

use App\Repositories\Contracts\PermissionRepositoryContract;
use Spatie\Permission\Models\Permission;

class PermissionRepository implements PermissionRepositoryContract
{
    public function all()
    {
        return Permission::orderBy('name')->get();
    }
    public function create(array $data)
    {
        return Permission::create($data);
    }
    public function edit(Permission $permission,array $data){
        $permission->update($data);
        return $permission->refresh();
    }
    public function delete(Permission $permission){
        $permission->delete();
    } 


}