<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::paginate(3);
        return view('department.index', compact('departments'));
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
        $date=$request->validated();
        Department::create($date);
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
        $date=$request->validated();
        $department->update($date);
        return redirect()->route('departments.index')->with('message', 'Department updated successfully.')->with('type','success');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department){

    if ($department->users()->exists()) {
        return redirect()->route('departments.index')->with('message', 'You can not delete this department because there is an Employees at this department')->with('type','waring');
    }
    $department->delete();
    return redirect()->route('departments.index')->with('message', 'Department deleted successfully.')->with('type','success');
    }
}
