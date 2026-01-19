<?php
namespace App\Repositories\Contracts;
use App\Models\Department;

interface DepartmentRepositoryContract
{

    public function paginate(int $perPage = 5);
    public function create(array $data);
    public function update(Department $department, array $data): bool;
    public function delete(Department $department): bool;

}