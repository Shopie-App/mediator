<?php

declare(strict_types=1);

namespace Shopie\Mediator;

use Shopie\Mediator\Contracts\MediatorInterface;
use Shopie\Mediator\Contracts\NotificationInterface;
use Shopie\Mediator\Contracts\RequestInterface;
use Shopie\Mediator\Finder\HandlerFinder;

class Mediator implements MediatorInterface
{
    public function __construct()
    {
    }

    public function send(RequestInterface $command): MediatorResult
    {
        // find handler
        $handler = (new HandlerFinder())->find($command);

        // execute and return result
        return (new $handler())->handle($command);
    }

    public function publish(NotificationInterface $notification): void
    {
        // find handler
        $handler = (new HandlerFinder())->find($notification);

        // execute
        (new $handler())->handle($notification);
    }
}