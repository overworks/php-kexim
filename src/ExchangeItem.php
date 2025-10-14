<?php

declare(strict_types=1);

namespace Minhyung\Kexim;

use ArrayAccess;
use LogicException;
use Minhyung\Kexim\Exceptions\ApiException;

class ExchangeItem implements ArrayAccess
{
    /** 조회 결과 */
    public readonly int $result;
    /** 통화 코드 */
    public readonly string $cur_unit;
    /** 국가/통화명 */
    public readonly string $cur_nm;
    /** 송금 받을 때 */
    public readonly float $ttb;
    /** 송금 보낼 때 */
    public readonly float $tts;
    /** 매매 기준율 */
    public readonly float $deal_bas_r;
    /** 장부가격 */
    public readonly float $bkpr;
    /** 연환가료율 */
    public readonly float $yy_efee_r;
    /** 10일환가료율 */
    public readonly float $ten_dd_efee_r;
    /** 서울외국환중개 매매기준율 */
    public readonly float $kftc_deal_bas_r;
    /** 서울외국환중개 장부가격 */
    public readonly float $kftc_bkpr;

    public function __construct(array $data)
    {
        if ($data['result'] !== 1) {
            throw ApiException::fromResultCode($data['result']);
        }

        $this->result = $data['result'];
        
        // (100)이 붙은 통화 단위는 100단위로 환율이 제공됨
        if (($pos = strpos($data['cur_unit'], '(100)')) !== false) {
            $factor = 0.01;
            $this->cur_unit = substr($data['cur_unit'], 0, $pos);
        } else {
            $factor = 1;
            $this->cur_unit = $data['cur_unit'];
        }
        $this->cur_nm = $data['cur_nm'];
        $this->ttb = floatval($data['ttb']) * $factor;
        $this->tts = floatval($data['tts']) * $factor;
        $this->deal_bas_r = floatval($data['deal_bas_r']) * $factor;
        $this->bkpr = floatval($data['bkpr']) * $factor;
        $this->yy_efee_r = floatval($data['yy_efee_r']) * $factor;
        $this->ten_dd_efee_r = floatval($data['ten_dd_efee_r']) * $factor;
        $this->kftc_deal_bas_r = floatval($data['kftc_deal_bas_r']) * $factor;
        $this->kftc_bkpr = floatval($data['kftc_bkpr']) * $factor;
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
