<?php

declare(strict_types=1);

namespace Shopie\Mediator\Exceptions;

use Exception;
use Throwable;

class HandlerUndefinedException extends Exception
{
    public function __construct(int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('Handler class name not defined, use as: #[MediatorHandler(MyHandler::class)]', $code, $previous);
    }
}