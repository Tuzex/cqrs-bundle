<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Cqrs;

use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface as MessageBus;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Tuzex\Bundle\Cqrs\Exception\HandledStampNotFoundException;
use Tuzex\Bundle\Cqrs\Exception\QueryHandlerNotFoundException;
use Tuzex\Cqrs\Query;
use Tuzex\Cqrs\QueryBus;
use Tuzex\Cqrs\Transfer\Dto;

final class MessengerQueryBus implements QueryBus
{
    private MessageBus $messageBus;

    public function __construct(MessageBus $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function execute(Query $query): Dto
    {
        try {
            $envelope = $this->messageBus->dispatch($query);
        } catch (NoHandlerForMessageException $e) {
            throw new QueryHandlerNotFoundException($query, $e);
        }

        $stamp = array_key_first($envelope->all(HandledStamp::class));
        if (!$stamp instanceof HandledStamp) {
            throw new HandledStampNotFoundException($query, $stamp);
        }

        return $stamp->getResult();
    }
}
