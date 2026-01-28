<?php 
namespace App\Repositories;
use App\Repositories\Contracts\TaskRepositoryContract;
use App\Models\Task;
class TaskRepository implements TaskRepositoryContract
{
    public function paginate(int $perPage)
    {
        return Task::orderBy('due_date','asc')->paginate($perPage);
    }

    public function paginateByUser(int $userId,$perPage)
    {
        return Task::where('user_id',$userId)
            ->orderBy('due_date','asc')
            ->paginate($perPage);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);
        return $task;
    }

    public function delete(Task $task): void
    {
        $task->update(['user_id' => null]);
        $task->delete();
    }
}