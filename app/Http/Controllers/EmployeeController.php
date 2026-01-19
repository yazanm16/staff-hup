<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Department;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Services\EmployeeService;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    public function __construct(protected EmployeeService $employeeService)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->employeeService->getEmployeeForIndex();
        return view('employees.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = $this->employeeService->getInfoForCreate();
        return view('employees.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEmployeeRequest $request)
    {
        $this->employeeService->storeEmployee($request->validated(), $request);
        return redirect()->route('employees.index')->with('message', 'Employee created successfully.')->with('type', 'success');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $employee)
    {
        $data = $this->employeeService->getInfoForEdit($employee);
        return view('employees.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, User $employee)
    {
        $this->employeeService->updateEmployee($employee, $request->validated(), $request->file('image'), $request->role);
        return redirect()->route('employees.index')->with('message', 'Employee Updated Successfully')->with('type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $employee)
    {
        try {
            $this->employeeService->deleteEmployee($employee);

            return redirect()
                ->route('employees.index')
                ->with('message', 'Employee Deleted Successfully')
                ->with('type', 'success');

        } catch (\Exception $e) {
            return redirect()
                ->route('employees.index')
                ->with('message', $e->getMessage())
                ->with('type', 'warning');
        }
    }
}