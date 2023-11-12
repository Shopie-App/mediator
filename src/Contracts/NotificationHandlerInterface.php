<?php

declare(strict_types=1);

namespace Shopie\Mediator\Contracts;

interface NotificationHandlerInterface
{
    /**
     * Handles a notification.
     */
    public function handle(NotificationInterface $notification): void;
}