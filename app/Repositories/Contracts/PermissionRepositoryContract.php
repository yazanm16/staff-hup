<?php
namespace App\Repositories\Contracts;
use Spatie\Permission\Models\Permission;
interface PermissionRepositoryContract
{
    public function all();
    public function create(array $data);
    public function edit(Permission $permission, array $data);
    public function delete(Permission $permission);

}
