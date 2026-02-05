<?php
namespace App\Exceptions\Attendance;
use App\Exceptions\BusinessException;

class AlreadyCheckedInException extends BusinessException
{
    protected int $statusCode = 409;
    public function __construct()
    {
        parent::__construct("User has already checked in.");
    }
}