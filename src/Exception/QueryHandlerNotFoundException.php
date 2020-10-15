<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Cqrs\Exception;

use LogicException;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Tuzex\Cqrs\Query;

/**
 * @deprecated since version 1.0.0
 */
final class QueryHandlerNotFoundException extends LogicException
{
    public function __construct(Query $query, NoHandlerForMessageException $exception)
    {
        $message = sprintf(
            'Handler for query "%s" not found. Check a namespace or first argument of the __invoke method on the handler class.',
            get_class($query)
        );

        parent::__construct($message, $exception->getCode(), $exception);
    }
}
