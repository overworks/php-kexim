<?php

declare(strict_types=1);

namespace Minhyung\Kexim;

use ArrayAccess;
use LogicException;

class Interest implements ArrayAccess
{
    /** @var InterestItem[] */
    public readonly array $items;

    public function __construct(array $data)
    {
        $this->items = array_map(fn ($item) => new InterestItem($item), $data);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->offsetExists($offset) ? $this->items[$offset] : null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new LogicException('Cannot modify readonly object');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new LogicException('Cannot modify readonly object');
    }
}
