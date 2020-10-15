<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Cqrs;

use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;
use Tuzex\Bundle\Cqrs\Exception\CommandHandlerNotFoundException;
use Tuzex\Cqrs\Command;
use Tuzex\Cqrs\CommandBus;

final class MessengerCommandBus implements CommandBus
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function execute(Command $command): void
    {
        try {
            $this->messageBus->dispatch($command);
        } catch (NoHandlerForMessageException $exception) {
            throw new CommandHandlerNotFoundException($command, $exception);
        }
    }
}
