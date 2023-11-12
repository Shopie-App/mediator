<?php

declare(strict_types=1);

namespace Shopie\Mediator\Contracts;

use Shopie\Mediator\MediatorResult;

interface RequestHandlerInterface
{
    /**
     * Handles a message.
     */
    public function handle(RequestInterface $message): MediatorResult;
}