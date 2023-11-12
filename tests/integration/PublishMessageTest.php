<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Shopie\Mediator\Attributes\MediatorHandler;
use Shopie\Mediator\Mediator;
use Shopie\Mediator\Notification;

final class PublishMessageTest extends TestCase
{
    public function testPublishMessage(): void
    {
        // init mediator
        $mediator = new Mediator();

        // our message
        $msg = new TestMessage(101, 'This is a test notification');

        // publish message
        $mediator->publish($msg);

        // assert
        $this->assertTrue(true);
    }
}

// our message struct
#[MediatorHandler(TestMessageHandler::class)]
class TestMessage extends Notification
{
    public function __construct(
        public readonly int $id,
        public readonly string $message
    ) {
    }
}

// our message's handler
class TestMessageHandler
{
    public function __construct()
    {
    }

    public function handle(TestMessage $notification): void
    {
        // .. pushes to some messaging queue
    }
}