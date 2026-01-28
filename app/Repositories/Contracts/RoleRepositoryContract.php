<?php
namespace App\Repositories\Contracts;
use Spatie\Permission\Models\Role;
interface RoleRepositoryContract
{
    public function all();
    public function getPermission();
    public function getRolePermissions(Role $role): array;
    public function create(array $data): Role;
    public function update(Role $role, array $data): Role;
    public function delete(Role $role): void;

}