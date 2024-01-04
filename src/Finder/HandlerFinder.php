<?php

declare(strict_types=1);

namespace Shopie\Mediator\Finder;

use ReflectionClass;
use Shopie\DiContainer\Contracts\ServiceProviderInterface;
use Shopie\Mediator\Attributes\MediatorHandler;
use Shopie\Mediator\Contracts\HandlerFinderInterface;
use Shopie\Mediator\Contracts\NotificationInterface;
use Shopie\Mediator\Contracts\RequestInterface;
use Shopie\Mediator\Exceptions\HandlerAttributeMissingException;
use Shopie\Mediator\Exceptions\NoContainerSetException;
use Shopie\Mediator\MediatorResult;

class HandlerFinder implements HandlerFinderInterface
{
    //private ReflectionClass $reflectedHandler;
    private NotificationInterface|RequestInterface $message;

    private string $handlerClassName;

    public function __construct(private ?ServiceProviderInterface $serviceProvider = null)
    {
    }

    public function find(NotificationInterface|RequestInterface $object): HandlerFinder
    {
        $this->message = $object;

        // introspect message class
        //$reflector = new \ReflectionClass($object);
        $reflector = new ReflectionClass($object);

        // get handler attribute
        $result = $reflector->getAttributes(MediatorHandler::class);

        // have one?
        if (empty($result)) {
            throw new HandlerAttributeMissingException($reflector->getName());
        }

        // get the handler's class name
        $this->handlerClassName = $result[0]->newInstance()->className();

        // return self
        return $this;
    }

    public function execute(): ?MediatorResult
    {
        return ($this->initConstructor())->handle($this->message);
    }

    private function initConstructor(): object
    {
        // reflect handler
        $reflector = new ReflectionClass($this->handlerClassName);

        // with no di container available
        if ($this->serviceProvider === null) {
            
            try {

                return $reflector->getConstructor() === null ? 
                $reflector->newInstanceWithoutConstructor() : 
                $reflector->newInstance();

            } catch (\ArgumentCountError $ex) {
                throw new NoContainerSetException($this->handlerClassName);
            }
        }

        // init constructor objects
        $params = $this->initConstructorParameters($reflector->getConstructor());

        // return handler object
        return $reflector->newInstance(...$params);
        
    }

    public function initConstructorParameters(\ReflectionMethod $constructor): array
    {
        // get constructor params
        $params = $constructor->getParameters();
        
        // have parameters?
        if (empty($params)) {
            return [];
        }

        $dependencies = [];
        
        foreach ($params as $param) {

            /** @var ReflectionNamedType $type */
            $type = $param->getType();

            $dependencies[] = !$type->isBuiltin() && !$param->isDefaultValueAvailable() 
            ? $this->serviceProvider->getService($type->getName()) : $param->getDefaultValue();
        }

        return $dependencies;
    }
}