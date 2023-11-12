<?php

declare(strict_types=1);

namespace Shopie\Mediator\Contracts;

interface HandlerFinderInterface
{
    /**
     * Finds the handler from the message's class attribute.
     */
    public function find(NotificationInterface|RequestInterface $object): string;
}