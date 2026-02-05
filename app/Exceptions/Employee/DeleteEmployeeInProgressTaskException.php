<?php
namespace App\Exceptions\Employee;
use App\Exceptions\BusinessException;
class DeleteEmployeeInProgressTaskException extends BusinessException
{
    protected int $statusCode = 422;
    public function __construct()
    {
        parent::__construct("Cannot delete employee with in-progress tasks.");
    }
}