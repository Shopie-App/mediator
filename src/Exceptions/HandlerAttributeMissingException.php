<?php

declare(strict_types=1);

namespace Shopie\Mediator\Exceptions;

class HandlerAttributeMissingException extends \Exception
{
    public function __construct($message, $code = 0, \Throwable $previous = null)
    {
        parent::__construct('Handler attribute not set in class: '.$message, $code, $previous);
    }
}