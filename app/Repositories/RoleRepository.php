<?php 
namespace App\Repositories;

use App\Repositories\Contracts\RoleRepositoryContract;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
class RoleRepository implements RoleRepositoryContract
{
    public function all(){
        return Role::with('permissions')->get();
    }
    public function getPermission()
    {
        return Permission::all();
    }
    public function getRolePermissions(Role $role): array
{
    return $role->permissions->pluck('name')->toArray();
}

    public function create(array $data): Role
    {
        return Role::create($data);
    }
    public function update(Role $role, array $data): Role
    {
        $role->update($data);
        return $role;
    }
    public function delete(Role $role): void
    {
        $role->delete();
    }
}
