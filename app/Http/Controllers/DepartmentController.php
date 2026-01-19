<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Models\Department;
use App\Models\User;
use App\Services\DepartmentService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(protected DepartmentService $departmentService)
    {
        
    }
    public function index()
    {
        $data = $this->departmentService->getDepartmentsForIndex();
        return view('department.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('department.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateDepartmentRequest $request){
        $this->departmentService->storeDepartment($request->validated());
        return redirect()->route('departments.index')->with('message', 'Department created successfully.')->with('type','success');
    }

    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        return view('department.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, Department $department){
        $this->departmentService->updateDepartment($department, $request->validated());
        return redirect()->route('departments.index')->with('message', 'Department updated successfully.')->with('type','success');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {

        try{
            $this->departmentService->deleteDepartment($department);
            return redirect()->route('departments.index')->with('message', 'Department deleted successfully.')->with('type','success');
        }catch(\Exception $e){
        return redirect()->route('departments.index')->with('message', 'You can not delete this department because there is an Employees at this department')->with('type','waring');

        }

    }
}