<?php

namespace App\Exception;

use Exception;
use Throwable;

class ThrowableException extends Exception
{
    public int $statusCode;

    public function __construct(private Throwable $exception)
    {
        $this->statusCode = 404;
    }
}
