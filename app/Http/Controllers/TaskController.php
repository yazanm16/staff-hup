<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks= Task::all();
        return view('tasks.index',compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::get();
        
        return view('tasks.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTaskRequest $request)
    {
       $data= $request->validated();
        $data['status'] = 'Pending';
        Task::create($data);
        return redirect()->route('tasks.index')->with('message','Task Created Successfully')->with('type','success');
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
    public function edit(Task $task)
    {
        $users = User::get();
        return view('tasks.edit',compact('task','users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        
        $data= $request->validated();
        $task->update($data);
        return redirect()->route('tasks.index')->with('message','Task Updated Successfully')->with('type','success');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->update(['user_id' => null]);
        $task->delete();
        return redirect()->route('tasks.index')->with('message', 'Task deleted successfully.')->with('type','success');
        
    }
}
