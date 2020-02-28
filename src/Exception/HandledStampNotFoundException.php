<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Cqrs\Exception;

use InvalidArgumentException;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Stamp\StampInterface;
use Tuzex\Cqrs\Query;

final class HandledStampNotFoundException extends InvalidArgumentException
{
    public function __construct(Query $query, $stamp = null)
    {
        $stamp = ($stamp instanceof StampInterface) ? get_class($stamp) : '';

        parent::__construct(
            sprintf('Query handler for the "%s" query returns "%s", instead of the "%s" stamp.', get_class($query), $stamp, HandledStamp::class)
        );
    }
}
