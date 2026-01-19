<?php
namespace App\Repositories;
use App\Models\Department;
use App\Repositories\Contracts\DepartmentRepositoryContract;
class DepartmentRepository implements DepartmentRepositoryContract
{
    public function paginate(int $perPage = 5)
    {
        return Department::paginate($perPage);
    }
    public function create(array $data)
    {
        return Department::create($data);
    }
    public function update(Department $department, array $data): bool
    {
        return $department->update($data);
    }
    public function delete(Department $department): bool
    {
        return $department->delete();
    }
}