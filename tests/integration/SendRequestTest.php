<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Shopie\Mediator\Attributes\MediatorHandler;
use Shopie\Mediator\Mediator;
use Shopie\Mediator\MediatorResult;
use Shopie\Mediator\MediatorResultStatus;
use Shopie\Mediator\Request;

final class SendRequestTest extends TestCase
{
    public function testSendRequest(): void
    {
        // init mediator
        $mediator = new Mediator();

        // our message, gets a test user
        $msg = new TestPublishMessage(50);

        // send message
        $result = $mediator->send($msg);

        // assert
        $this->assertInstanceOf(MediatorResult::class, $result);
        $this->assertSame(MediatorResultStatus::SUCCESS, $result->status);
        $this->assertSame(50, $result->result()->id);
        $this->assertSame('my_username', $result->result()->username);
    }
}

// our message struct
#[MediatorHandler(TestPublishMessageHandler::class)]
class TestPublishMessage extends Request
{
    public function __construct(public readonly int $id)
    {
    }
}

// our message's handler
class TestPublishMessageHandler
{
    public function __construct()
    {
    }

    public function handle(TestPublishMessage $notification): MediatorResult
    {
        // find user by the id: $notification->id
        $user = new TestPublishUser($notification->id, 'my_username');

        // set it to result
        return new MediatorResult(null, $user);
    }
}

// our user entity
class TestPublishUser
{
    public function __construct(
        public int $id,
        public string $username
    ) {
    }
}