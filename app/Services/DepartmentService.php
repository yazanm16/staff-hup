<?php
namespace App\Services;

use App\Exceptions\Department\DepartmentHasEmployeesException;
use App\Models\Department;
use App\Repositories\Contracts\DepartmentRepositoryContract;
use Symfony\Component\HttpFoundation\Request;

class DepartmentService
{
    public function __construct(protected DepartmentRepositoryContract $departmentRepository)
    {
        
    }
    public function getDepartmentsForIndex(){
        return [
            'departments' => $this->departmentRepository->paginate(4)
        ];
        
    }
    public function storeDepartment(array $data){
        return $this->departmentRepository->create($data);
    }

    public function updateDepartment(Department $department, array $data): bool
    {
        return $this->departmentRepository->update($department, $data);
    }

    public function deleteDepartment(Department $department): bool
    {
        if($department->users()->exists()){
            throw new DepartmentHasEmployeesException();
        }
        return $this->departmentRepository->delete($department);
    }



    
}