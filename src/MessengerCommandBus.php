<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Cqrs;

use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface as MessageBus;
use Tuzex\Bundle\Cqrs\Exception\CommandHandlerNotFoundException;
use Tuzex\Cqrs\Command;
use Tuzex\Cqrs\CommandBus;

final class MessengerCommandBus implements CommandBus
{
    /**
     * @var MessageBus
     */
    private MessageBus $messageBus;

    public function __construct(MessageBus $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function execute(Command $command): void
    {
        try {
            $this->messageBus->dispatch($command);
        } catch (NoHandlerForMessageException $e) {
            throw new CommandHandlerNotFoundException($command, $e);
        }
    }
}
