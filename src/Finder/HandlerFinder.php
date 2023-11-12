<?php

declare(strict_types=1);

namespace Shopie\Mediator\Finder;

use Shopie\Mediator\Attributes\MediatorHandler;
use Shopie\Mediator\Contracts\HandlerFinderInterface;
use Shopie\Mediator\Contracts\NotificationInterface;
use Shopie\Mediator\Contracts\RequestInterface;

class HandlerFinder implements HandlerFinderInterface
{
    public function __construct()
    {
    }

    public function find(NotificationInterface|RequestInterface $object): string
    {
        // introspect message class
        $reflector = new \ReflectionClass($object);

        // get handler attribute
        $result = $reflector->getAttributes(MediatorHandler::class);

        // have one?
        if (empty($result)) {
            return '';
        }

        // get the handler's class name
        return $result[0]->newInstance()->className();
    }
}