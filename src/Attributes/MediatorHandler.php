<?php

declare(strict_types=1);

namespace Shopie\Mediator\Attributes;

use Attribute;

/**
 * Identifies a handler for a notification or request.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class MediatorHandler
{
    private string $className;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function className(): string
    {
        return $this->className;
    }
}