<?php

declare(strict_types=1);

namespace Shopie\Mediator\Exceptions;

use Exception;
use Throwable;

class NoContainerSetException extends Exception
{
    public function __construct(string $message, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('Cannot instantiate handler constructor parameters without a DI container, class: ' . $message, $code, $previous);
    }
}