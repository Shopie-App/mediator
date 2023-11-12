# Mediator

Essential mediator implementation.

### Send a request and get a response back

```php
// A message we want to send to a handler
// Should extend Request class
// Define the worker handler with the MediatorHandler attribute
#[MediatorHandler(RequestNotificationHandler::class)]
class RequestNotification extends Request
{
    public function __construct()
    {
    }
}

// A handler that does something with the message
// our message's handler
class RequestNotificationHandler
{
    public function __construct()
    {
    }

    public function handle(RequestNotification $notification): MediatorResult
    {
        //...do work

        // return result
        // 1st argument: error string, 2nd argument: result object
        return new MediatorResult(null, null);
    }
}

// init Mediator, send message, get result back
$result = (new Mediator())->send(new RequestNotification());

// check $result->status
// failed equals:
MediatorResultStatus::FAILED
// success equals:
MediatorResultStatus::SUCCESS
```

### Publish a message to a queue

```php
// A message we want to send to a handler
// Should extend Notification class
// Define the worker handler with the MediatorHandler attribute
#[MediatorHandler(TestMessageHandler::class)]
class TestMessage extends Notification
{
    public function __construct(
        public readonly int $id,
        public readonly string $message
    ) {
    }
}

// A handler that does something with the message
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

// init Mediator, publish message
 (new Mediator())->publish(new TestMessage(1, 'This is a test notification'));
```