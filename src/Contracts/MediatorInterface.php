<?php

declare(strict_types=1);

namespace Shopie\Mediator\Contracts;

use Shopie\DiContainer\Contracts\ServiceProviderInterface;
use Shopie\Mediator\MediatorResult;

interface MediatorInterface
{
    /**
     * Set service provider and load services from DI container.
     * 
     * @link https://github.com/Shopie-App/di-container Compatible with Shopie DI Container.
     * 
     * @param ServiceProviderInterface $serviceProvider Provider interface.
     */
    public function setServiceProvider(ServiceProviderInterface $serviceProvider): void;

    /**
     * Send a request to an object and get a response back.
     * 
     * @param RequestInterface $request The request to handle.
     * 
     * @return MediatorResult
     */
    public function send(RequestInterface $request): MediatorResult;

    /**
     * Publish a message to many subscribers.
     * 
     * @param NotificationInterface $notification The message to handle.
     */
    public function publish(NotificationInterface $notification): void;
}