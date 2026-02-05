<?php
namespace App\Exceptions\Attendance;
use App\Exceptions\BusinessException;
use Throwable;

class NoActiveCheckInException extends BusinessException
{
    protected int $statusCode = 404;
    public function __construct()
    {
        parent::__construct("No active check-in found for the user.");
    }
}