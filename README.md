# Mediator

Essential mediator implementation.

Use Mediator when you want to handle communication between loosely coupled objects.

## Installation

```json
php composer require shopie/mediator
```

### Send a request and get a response back

```php
// A message we want to send to a handler
// Should extend Request class
// Define the worker handler with the MediatorHandler attribute
#[MediatorHandler(RequestNotificationHandler::class)]
class RequestNotification extends Request
{
    public function __construct(
        private int $id,
        private string $name,
        private bool $isActive
    ) {
    }

    public function isActive(): bool
    {
        return $this->isActive;
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
        // example error return
        if (!$notification->isActive()) {
            return new MediatorResult('User is not activated');
        }

        // ...do work

        // return result
        // 1st argument: error string, 2nd argument: result object
        // result object can be anything
        return new MediatorResult(null, true);
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

### Injecting dependencies to handlers

Use [shopie/di-container](https://github.com/Shopie-App/di-container) IoC container if you want to inject dependencies to handlers.

```php
// Prototype example container initialization in an App class
class App
{
    /**
     * Services are added to the container.
     */
    private ServiceContainerInterface $container;

    /**
     * Services are requested from provider.
     */
    private ServiceProviderInterface $provider;

    public function __construct()
    {
    }

    public function initContainer()
    {
        $collection = new ServiceCollection();

        $this->container = new ServiceContainer($collection);

        $this->provider = new ServiceProvider($collection);
    }

    public function addServices()
    {
        // add mediator to container
        $this->container->addScoped(MediatorInterface::class, Mediator::class);

        // init mediator
        $mediator = $this->provider->getService(Mediator::class);

        // add service provider to mediator
        $mediator->setServiceProvider($this->provider);
    }
}

// Using the App class
$app = new App();
$app->initContainer();
$app->addServices();
```

Now objects can be injected to the handlers. 

```php
// A handler with dependencies
class TestMessageHandler
{
    public function __construct(private MyRepository $repository)
    {
    }

    public function handle(TestMessage $notification): void
    {
        // do work
        $this->repository->add($notification);
    }
}
```