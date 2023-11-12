<?php

declare(strict_types=1);

namespace Shopie\Mediator;

class MediatorResult
{
    public MediatorResultStatus $status;

    /**
     * @param string|null $error The error message returned from the handler or null if success.
     * @param mixed $result The returned result from the handler.
     */
    public function __construct(
        private ?string $error,
        private mixed $result = null
    ) {
        if ($error == '') {
            $this->status = MediatorResultStatus::SUCCESS;
        } else {
            $this->status = MediatorResultStatus::FAILED;
        }
    }

    public function error(): string
    {
        return $this->error;
    }

    public function result(): mixed
    {
        return $this->result;
    }
}