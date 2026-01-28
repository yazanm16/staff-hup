<?php
namespace App\Services;
use App\Repositories\Contracts\RoleRepositoryContract;
use Spatie\Permission\Models\Role;
class RoleService 
{
    public function __construct(protected RoleRepositoryContract $roleRepository)
    {

    }
    public function getAll(){
        return $this->roleRepository->all();
    }
    public function getAllPermission(){
        return $this->roleRepository->getPermission();
    }
    public function getRolePermissions(Role $role){
        return $this->roleRepository->getRolePermissions($role);
    }
    public function create(array $data){
        $data['guard_name'] = 'web';
        $role = $this->roleRepository->create($data);
        $role->syncPermissions($data['permissions'] ?? []);
        return $role;
    }
    public function update(Role $role, array $data): Role
    {
        $data['guard_name'] = 'web';
        $role = $this->roleRepository->update($role, $data);
        $role->syncPermissions($data['permissions'] ?? []);
        return $role;
    }
    public function delete(Role $role){
        if ($role->name === 'admin') {
            throw new \Exception('Cannot delete admin role');
        }

        if ($role->users()->exists()) {
            throw new \Exception('Role assigned to users');
        }

        $this->roleRepository->delete($role);
    }
}