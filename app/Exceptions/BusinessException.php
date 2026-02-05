<?php
namespace App\Exceptions;
use Exception;
abstract class BusinessException extends Exception
{
    protected int $statusCode=400;
    public function statusCode():int 
    {
        return $this->statusCode;
    }
}