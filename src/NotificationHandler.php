<?php

declare(strict_types=1);

namespace Shopie\Mediator;

use Shopie\Mediator\Contracts\NotificationHandlerInterface;

abstract class NotificationHandler implements NotificationHandlerInterface
{
    abstract public function handle(mixed $notification): void;
}