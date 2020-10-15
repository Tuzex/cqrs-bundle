<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Cqrs\Exception;

use Tuzex\Cqrs\Query;

final class UnprocessedQueryException extends \LogicException
{
    public function __construct(Query $query)
    {
        /*
         * @todo PHP8 => $query::class
         */
        parent::__construct(sprintf('Query "%s" has not been processed.', get_class($query)));
    }
}
