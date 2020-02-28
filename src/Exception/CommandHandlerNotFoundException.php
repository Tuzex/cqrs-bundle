<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Cqrs\Exception;

use LogicException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Tuzex\Cqrs\Command;

final class CommandHandlerNotFoundException extends LogicException
{
    public function __construct(Command $command, NoHandlerForMessageException $exception)
    {
        $message = sprintf(
            'Handler for command "%s" not found. Check a namespace or first argument of the __invoke method on the handler class.',
            get_class($command)
        );

        parent::__construct($message, $exception->getCode(), $exception);
    }
}
