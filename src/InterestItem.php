<?php

declare(strict_types=1);

namespace Minhyung\Kexim;

use ArrayAccess;
use LogicException;
use Minhyung\Kexim\Exceptions\ApiException;

class InterestItem implements ArrayAccess
{
    /** 조회 결과 */
    public readonly int $result;
    /** 대출기간 */
    public readonly string $sfln_intrc_nm;
    /** 고정기준금리(%) */
    public readonly float $int_r;

    public function __construct(array $data)
    {
        if ($data['result'] !== 1) {
            throw ApiException::fromResultCode($data['result']);
        }

        $this->result = $data['result'];
        $this->sfln_intrc_nm = $data['sfln_intrc_nm'];
        $this->int_r = floatval($data['int_r']);
    }

    public function offsetExists(mixed $offset): bool
    {
        return property_exists($this, $offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->offsetExists($offset) ? $this->$offset : null;
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
