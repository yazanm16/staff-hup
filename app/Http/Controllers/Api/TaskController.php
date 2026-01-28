<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateStatusRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected TaskService $taskService
    ) {}

    public function index()
    {
        return $this->success(
            ['tasks'=>TaskResource::collection($this->taskService->list(5))]
        );
    }

    public function store(CreateTaskRequest $request)
    {
        $task = $this->taskService->create($request->validated());

        return $this->success(
            ['task'=>new TaskResource($task)],
            'Task created',
            201
        );
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task = $this->taskService->update($task, $request->validated());

        return $this->success(
            ['task'=>new TaskResource($task)],
            'Task updated'
        );
    }

    public function destroy(Task $task)
    {
        $this->taskService->delete($task);

        return $this->success([], 'Task deleted');
    }

    public function myTasks()
    {
        return $this->success(
            ['tasks'=>TaskResource::collection(
                $this->taskService->myTasks(Auth::id(),5)
            )]
        );
    }

    public function updateStatus(UpdateStatusRequest $request, Task $task)
    {
        $task = $this->taskService->updateStatus(
            $task,
            Auth::id(),
            $request->validated()['status']
        );

        return $this->success(
            ['task'=>new TaskResource($task)],
            'Status updated'
        );
    }
}
