<?php

declare(strict_types=1);

namespace Shopie\Mediator;

enum MediatorResultStatus
{
    case FAILED;

    case SUCCESS;
}