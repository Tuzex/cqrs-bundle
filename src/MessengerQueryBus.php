<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Cqrs;

use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Tuzex\Bundle\Cqrs\Exception\QueryHandlerNotFoundException;
use Tuzex\Bundle\Cqrs\Exception\UnprocessedQueryException;
use Tuzex\Cqrs\Query;
use Tuzex\Cqrs\QueryBus;

final class MessengerQueryBus implements QueryBus
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function execute(Query $query): object
    {
        try {
            $envelope = $this->messageBus->dispatch($query);
        } catch (NoHandlerForMessageException $exception) {
            throw new QueryHandlerNotFoundException($query, $exception);
        }

        $stamp = $envelope->last(HandledStamp::class);
        if (!$stamp instanceof HandledStamp) {
            throw new UnprocessedQueryException($query);
        }

        return $stamp->getResult();
    }
}
