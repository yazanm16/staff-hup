<?php
namespace App\Repositories;

use App\Models\Department;
use App\Models\User;
use App\Repositories\Contracts\EmployeeRepositoryContract;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;

class EmployeeRepository implements EmployeeRepositoryContract
{
    public function paginate(int $perPage = 5)
    {
        return User::paginate($perPage);
    }
    public function getDepartments()
    {
        return Department::get();
    }
    public function getRoles()
    {
        return Role::get();
    }
    public function create(array $data)
    {
        return User::create($data);
    }
    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }
    public function deletePhoto(User $employee)
    {
        if (!$employee->photo) {
            return;
        }
        Storage::disk('public')->delete($employee->photo->path);
        $employee->photo->delete();
    }
    public function storePhoto(User $employee,UploadedFile $image)
    {
        $path = $image->store('employees', 'public');
        $employee->photo()->create([
            'path' => $path,
            'disk' => 'public',
        ]);
    }
    public function replacePhoto(User $employee, UploadedFile $image): void
    {
        $this->deletePhoto($employee);
        $this->storePhoto($employee, $image);
    }
    public function syncRole(User $employee, string $role): void
    {
        $employee->syncRoles($role);
    }
    public function delete(User $employee): bool
    {
        $this->deletePhoto($employee);
        $employee->syncRoles([]);
        $employee->syncPermissions([]);
        return $employee->delete();
    }
}