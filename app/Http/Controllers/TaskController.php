<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateStatusRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\TaskService;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(
        protected TaskService $taskService
    ) {}

    public function index()
    {
        return view('tasks.index', [
            'tasks' => $this->taskService->list(5)
        ]);
    }

    public function create()
    {
        return view('tasks.create', [
            'users' => User::all()
        ]);
    }

    public function store(CreateTaskRequest $request)
    {
        $this->taskService->create($request->validated());

        return redirect()->route('tasks.index')
            ->with('message','Task Created Successfully')
            ->with('type','success');
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', [
            'task' => $task,
            'users' => User::all()
        ]);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->taskService->update($task, $request->validated());

        return redirect()->route('tasks.index')
            ->with('message','Task Updated Successfully')
            ->with('type','success');
    }

    public function destroy(Task $task)
    {
        $this->taskService->delete($task);

        return back()
            ->with('message','Task deleted successfully')
            ->with('type','success');
    }

    public function myTasks()
    {
        return view('tasks.myTasks', [
            'tasks' => $this->taskService->myTasks(Auth::id(),5)
        ]);
    }

    public function updateStatus(UpdateStatusRequest $request, Task $task)
    {
        $this->taskService->updateStatus(
            $task,
            Auth::id(),
            $request->validated()['status']
        );

        return back()
            ->with('message','Task status updated successfully')
            ->with('type','success');
    }  
}
