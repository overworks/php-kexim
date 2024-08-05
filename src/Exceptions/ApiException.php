<?php

declare(strict_types=1);

namespace Minhyung\Kexim\Exceptions;

use RuntimeException;

class ApiException extends RuntimeException
{
    const RESULT_CODES = [
        1 => '성공',
        2 => 'DATA 코드 오류',
        3 => '인증코드 오류',
        4 => '일일제한횟수 마감',
    ];

    public static function fromResultCode(int $code): self
    {
        return new self(static::RESULT_CODES[$code] ?? 'Unknown error');
    }
}
