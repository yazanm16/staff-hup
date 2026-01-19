<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\EmployeeService;

class EmployeeController extends Controller
{
    public function __construct(protected EmployeeService $employeeService)
    {
        
    }
    public function index(){
        return EmployeeResource::collection($this->employeeService->getEmployeeForIndex()['users']);
    }

    public function store(CreateEmployeeRequest $request){
        $employee = $this->employeeService->storeEmployee($request->validated(), $request);
        return response()->json([
            'message' => 'Employee Created Successfully',
            'data' => new EmployeeResource($employee)
        ], 201);
    }
    public function edit(User $employee)
    {
        return response()->json([
            'employee'    => new EmployeeResource($employee),
            'departments' => $this->employeeService->getInfoForEdit($employee)['departments'],
            'roles'       => $this->employeeService->getInfoForEdit($employee)['roles'],
        ]);
        }
    public function update(UpdateEmployeeRequest $request,User $employee){
        $employee = $this->employeeService->updateEmployee($employee, $request->validated(), $request->file('image'), $request->role);
        return response()->json([
            'message' => 'Employee Updated Successfully',
            'data' => new EmployeeResource($employee)
        ], 200);
    }
    public function destroy(User $employee)
    {
        try {
            $this->employeeService->deleteEmployee($employee);

            return response()->json([
                'message' => 'Employee Deleted Successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
