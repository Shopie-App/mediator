<?php

declare(strict_types=1);

namespace Shopie\Mediator;

use Shopie\DiContainer\Contracts\ServiceProviderInterface;
use Shopie\Mediator\Contracts\MediatorInterface;
use Shopie\Mediator\Contracts\NotificationInterface;
use Shopie\Mediator\Contracts\RequestInterface;
use Shopie\Mediator\Finder\HandlerFinder;

class Mediator implements MediatorInterface
{
    private ServiceProviderInterface $serviceProvider;

    public function __construct()
    {
    }

    public function setServiceProvider(ServiceProviderInterface $serviceProvider): void
    {
        $this->serviceProvider = $serviceProvider;
    }

    public function send(RequestInterface $command): MediatorResult
    {
        return (new HandlerFinder($this->serviceProvider ?? null))->find($command)->execute();
    }

    public function publish(NotificationInterface $notification): void
    {
        (new HandlerFinder($this->serviceProvider ?? null))->find($notification)->execute();
    }
}