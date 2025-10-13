<?php

declare(strict_types=1);

namespace Minhyung\Kexim;

class Interest
{
    /** 조회 결과 */
    public readonly ResponseResult $result;
    /** 대출기간 */
    public readonly string $sfln_intrc_nm;
    /** 고정기준금리(%) */
    public readonly float $int_r;

    public function __construct(array $result)
    {
        $this->result = ResponseResult::from($result['result']);
        $this->sfln_intrc_nm = $result['sfln_intrc_nm'];
        $this->int_r = floatval($result['int_r']);
    }
}
