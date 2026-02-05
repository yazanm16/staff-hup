<?php 
namespace App\Services;
use App\Exceptions\Task\TaskAccessDeniedException;
use App\Models\Task;

use App\Repositories\Contracts\TaskRepositoryContract;

class TaskService 
{
    public function __construct(protected TaskRepositoryContract $taskRepository)
    {
    }
    public function list(int $perPage)
    {
        return $this->taskRepository->paginate($perPage);
    }

    public function myTasks(int $userId,int $perPage)
    {
        return $this->taskRepository->paginateByUser($userId,$perPage);
    }

    public function create(array $data): Task
    {
        $data['status'] = 'Pending';
        return $this->taskRepository->create($data);
    }

    public function update(Task $task, array $data): Task
    {
        return $this->taskRepository->update($task, $data);
    }

    public function delete(Task $task): void
    {
        $this->taskRepository->delete($task);
    }

    public function updateStatus(Task $task, int $userId, string $status): Task
    {
        if ($task->user_id !== $userId) {
            throw new TaskAccessDeniedException();

        }

        return $this->taskRepository->update($task, [
            'status' => $status
        ]);
    }
}