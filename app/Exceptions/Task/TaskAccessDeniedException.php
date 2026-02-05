<?php
namespace App\Exceptions\Task;

use App\Exceptions\BusinessException;

class TaskAccessDeniedException extends BusinessException
{
    protected int $statusCode = 403;

    public function __construct()
    {
        parent::__construct('You are not allowed to access this task.');
    }
}
