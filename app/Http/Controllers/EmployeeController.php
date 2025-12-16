<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Department;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users=User::paginate(2);
        $departments = Department::get();
        return view('employees.index',compact('users','departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::get();
        return view('employees.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEmployeeRequest $request)
    {
        $data=$request->validated();
        if($request->hasFile('image')){
            $image = $request->image;
            $new_image = time() . '-' . $image->getClientOriginalName();
            $image->StoreAs('employees', $new_image, 'public');
            $data['image'] =  $new_image;
        }
        User::create($data);
        return redirect()->route('employees.index')->with('message','Employee created successfully.')->with('type','success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $employee)
    {
        $departments = Department::get();
        return view('employees.edit',compact('employee','departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, User $employee)
    {
        $data=$request->validated();
        if($request->hasFile('image') ){
            if ($employee->image && Storage::disk('public')->exists('employees/' . $employee->image)) {
            Storage::disk('public')->delete('employees/' . $employee->image);
        }
            $image = $request->image;
            $new_image = time() . '-' . $image->getClientOriginalName();
            $image->StoreAs('employees', $new_image, 'public');
            $data['image'] =  $new_image;
        }
        $employee->update($data);
        return redirect()->route('employees.index')->with('message','Employee Updated Successfully')->with('type','success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $employee)
    {
        if($employee->image){
            Storage::disk('public')->delete("employees/" . $employee->image);
        }
        if($employee->tasks){
            $employee->tasks()->update(['user_id' => null]);
        }
        $employee->delete();
        return redirect()->route('employees.index')->with('message', 'Employee Deleted successfully.')->with('type','success');
        
    }
}
