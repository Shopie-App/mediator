<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Shopie\DiContainer\ServiceCollection;
use Shopie\DiContainer\ServiceContainer;
use Shopie\DiContainer\ServiceProvider;
use Shopie\Mediator\Attributes\MediatorHandler;
use Shopie\Mediator\Exceptions\HandlerAttributeMissingException;
use Shopie\Mediator\Exceptions\HandlerUndefinedException;
use Shopie\Mediator\Mediator;
use Shopie\Mediator\MediatorResult;
use Shopie\Mediator\MediatorResultStatus;
use Shopie\Mediator\Request;

final class SendRequestTest extends TestCase
{
    /*public function testSendRequestNoHandlerException(): void
    {
        // init mediator
        $mediator = new Mediator();

        // expect exception
        $this->expectException(HandlerUndefinedException::class);
        
        // send message
        $mediator->send(new TestPublishMessageNoHandler());
    }

    public function testSendRequestNoHandlerAttributeException(): void
    {
        // init mediator
        $mediator = new Mediator();

        // expect exception
        $this->expectException(HandlerAttributeMissingException::class);
        
        // send message
        $mediator->send(new TestPublishMessageNoHandlerAttribute());
    }

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
    }*/

    public function testSendRequestWithDi(): void
    {
        // init di
        $collection = new ServiceCollection();
        $container = new ServiceContainer($collection);
        $provider = new ServiceProvider($collection);

        // init mediator
        $mediator = new Mediator();

        // set container provider
        $mediator->setServiceProvider($provider);

        // add test services to di
        $container->addScoped(DiObjectA::class);
        $container->addScoped(DiObjectB::class);

        // our message, gets a test user
        $msg = new TestPublishMessageDi(101);

        // send message
        $result = $mediator->send($msg);

        // assert
        $this->assertInstanceOf(MediatorResult::class, $result);
        $this->assertSame(MediatorResultStatus::SUCCESS, $result->status);
        $this->assertSame(101, $result->result()->id);
        $this->assertSame('DiObjectA', $result->result()->varA);
        $this->assertSame('DiObjectB', $result->result()->varB);
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

// our message struct with no handler class defined
#[MediatorHandler]
class TestPublishMessageNoHandler extends Request
{
    public function __construct()
    {
    }
}

// our message struct with no handler attribute
class TestPublishMessageNoHandlerAttribute extends Request
{
    public function __construct()
    {
    }
}

// our message struct with a handler that has params in the constructor.
#[MediatorHandler(TestPublishMessageDiHandler::class)]
class TestPublishMessageDi extends Request
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

// our message's handler with params
class TestPublishMessageDiHandler
{
    public function __construct(private DiObjectA $objA, private DiObjectB $objB)
    {
    }

    public function handle(TestPublishMessageDi $notification): MediatorResult
    {
        return new MediatorResult(null, (object) [
            'id' => $notification->id,
            'varA' => $this->objA->name(),
            'varB' => $this->objB->name()
        ]);
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

// class loaded from di
class DiObjectA
{
    private string $name = 'DiObjectA';

    public function __construct()
    {
    }

    public function name(): string
    {
        return $this->name;
    }
}

// class loaded from di
class DiObjectB
{
    private string $name = 'DiObjectB';

    public function __construct()
    {
    }

    public function name(): string
    {
        return $this->name;
    }
}