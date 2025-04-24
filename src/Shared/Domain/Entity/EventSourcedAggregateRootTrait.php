<?php

declare(strict_types=1);

namespace Candice\Contexts\Shared\Domain\Entity;

use Candice\Contexts\Shared\Domain\Event\DomainEvent;
use LogicException;
use ReflectionClass;

trait EventSourcedAggregateRootTrait
{
    /**
     * @var list<DomainEvent>
     */
    private array $events = [];

    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }

    protected function recordThat(DomainEvent $event): mixed
    {
        $this->events[] = $event;

        return $this->apply($event);
    }

    public function apply(DomainEvent $event): mixed
    {
        $callable = $this->getApplyMethodHandler($event);
        return $callable($event);
    }

    /**
     * @template T of DomainEvent
     *
     * @return callable(T): mixed
     */
    public function getApplyMethodHandler(DomainEvent $event): callable
    {
        $methodName = 'apply' . new ReflectionClass($event)->getShortName();
        if (!method_exists($this, $methodName)) {
            // @codeCoverageIgnoreStart
            throw new LogicException(
                sprintf(
                    'Method %s does not exist in class %s',
                    $methodName,
                    static::class,
                ),
            );
            // @codeCoverageIgnoreEnd
        }

        return [$this, $methodName];
    }
}
