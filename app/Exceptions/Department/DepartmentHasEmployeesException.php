<?php
namespace App\Exceptions\Department;
use App\Exceptions\BusinessException;
class DepartmentHasEmployeesException extends BusinessException
{
    protected int $statusCode = 422;
    public function __construct()
    {
        parent::__construct("Department has employees and cannot be deleted.");
    }
}