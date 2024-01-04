<?php

declare(strict_types=1);

namespace Shopie\Mediator\Attributes;

use Attribute;
use Shopie\Mediator\Exceptions\HandlerUndefinedException;

/**
 * Identifies a handler for a notification or a request.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class MediatorHandler
{
    public function __construct(private string $className = '')
    {
        if ($this->className == '') {
            throw new HandlerUndefinedException();
        }
    }

    public function className(): string
    {
        return $this->className;
    }
}