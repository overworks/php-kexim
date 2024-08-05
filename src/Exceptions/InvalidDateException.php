<?php

declare(strict_types=1);

namespace Minhyung\Kexim\Exceptions;

use InvalidArgumentException;
use Throwable;

class InvalidDateException extends InvalidArgumentException
{
    public function __construct(string $message = "Invalid date", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
