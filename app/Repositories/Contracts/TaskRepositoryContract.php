<?php
namespace App\Repositories\Contracts;
use App\Models\Task;
interface TaskRepositoryContract
{
    public function paginate(int $perPage);
    public function paginateByUser(int $userId,int $perPage);
    public function create(array $data): Task;
    public function update(Task $task, array $data): Task;
    public function delete(Task $task): void;
}