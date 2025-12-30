<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Department;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users=User::paginate(5);
        $departments = Department::get();
        return view('employees.index',compact('users','departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::get();
        $roles = Role::get();
        return view('employees.create', compact('departments','roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEmployeeRequest $request){
        $data=$request->validated();
        $user=User::create($data);
        $user->assignRole($request->role);
        if($request->hasFile('image')){
            $path=$request->file('image')->store('employees','public');
             $user->photo()->create([
            'path' => $path,
            'disk' => 'public',
        ]);
        }
        return redirect()->route('employees.index')->with('message','Employee created successfully.')->with('type','success');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $employee)
    {
        $departments = Department::get();
        $roles = Role::get();
        return view('employees.edit',compact('employee','departments','roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, User $employee)
    {
        $data=$request->validated();
        $employee->update($data);
        $employee->syncRoles([$request->role]);
        if($request->hasFile('image') ){
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo->path);
                $employee->photo->delete();
        }
        $path=$request->file('image')->store('employees','public');
        $employee->photo()->create([
        'path' => $path,
        'disk' => 'public',
        ]);
    }
        return redirect()->route('employees.index')->with('message','Employee Updated Successfully')->with('type','success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $employee)
    {
        if($employee->tasks){
            if($employee->tasks()->where('status', 'Completed')->exists()){
                $employee->tasks()->delete();
            }
            else if($employee->tasks()->where('status', 'In-Progress')->exists()){
                return redirect()->route('employees.index')->with('message', 'Can not delete employee with tasks In Progress.')->with('type','warning');
            }
            $employee->tasks()->update(['user_id' => null]);
        }
        if ($employee->attendances()->exists()) {
            $employee->attendances()->delete();
        }
        if($employee->photo){
            Storage::disk('public')->delete($employee->photo->path);
            $employee->photo->delete();
        }
        $employee->syncRoles([]);
        $employee->syncPermissions([]);
        $employee->delete();
        return redirect()->route('employees.index')->with('message', 'Employee Deleted successfully.')->with('type','success');
        
    }
}
