<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\EmployeeRepositoryContract;
use Symfony\Component\HttpFoundation\Request;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EmployeeService
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected EmployeeRepositoryContract $employeeRepository)
    {

    }
    public function getEmployeeForIndex(){
        return [
            'users' => $this->employeeRepository->paginate(5),
            'departments' => $this->employeeRepository->getDepartments()
        ];
    }
    public function getInfoForCreate(){
        return [
            'departments' => $this->employeeRepository->getDepartments(),
            'roles' => $this->employeeRepository->getRoles()
        ];
    }
    public function storeEmployee(array $data,Request $request){
        $employee = $this->employeeRepository->create($data);
        if($request->role){
            $this->employeeRepository->syncRole($employee, $request->role);
        }
        
        if($request->hasFile('image')){
            $this->employeeRepository->storePhoto($employee, $request->file('image'));
        }
        return $employee;
    }

    public function getInfoForEdit(User $employee){
        return [
            'employee' => $employee,
            'departments' => $this->employeeRepository->getDepartments(),
            'roles' => $this->employeeRepository->getRoles()
        ];
    }

    public function updateEmployee(User $employee, array $data,?UploadedFile $image,?string $role): User
    {
        $this->employeeRepository->update($employee, $data);
        if ($role) {
            $this->employeeRepository->syncRole($employee, $role);
        }
        if($image){
            $this->employeeRepository->replacePhoto($employee, $image);
        }
        return $employee->load(['photo','roles']);
    }
    public function deleteEmployee(User $employee){
        if($employee->tasks){
            if($employee->tasks()->where('status', 'Completed')->exists()){
                $employee->tasks()->delete();
            }
            else if($employee->tasks()->where('status', 'In-Progress')->exists()){
                throw new \Exception('Cannot delete employee with in-progress tasks.');
            }
            $employee->tasks()->update(['user_id' => null]);
        }
        if ($employee->attendances()->exists()) {
            $employee->attendances()->delete();
        }
        $this->employeeRepository->delete($employee);
    }
}
