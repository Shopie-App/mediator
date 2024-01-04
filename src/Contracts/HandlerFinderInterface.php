<?php

declare(strict_types=1);

namespace Shopie\Mediator\Contracts;

use Shopie\Mediator\Finder\HandlerFinder;
use Shopie\Mediator\MediatorResult;

interface HandlerFinderInterface
{
    /**
     * Finds the handler from the message's class attribute.
     * 
     * @return HandlerFinder Returns self.
     */
    public function find(NotificationInterface|RequestInterface $object): HandlerFinder;

    /**
     * Executes the handler.
     * 
     * @return null|MediatorResult Returns null with publish() or MediatorResult for send().
     */
    public function execute(): ?MediatorResult;
}