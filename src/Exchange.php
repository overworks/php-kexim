<?php

declare(strict_types=1);

namespace Minhyung\Kexim;

class Exchange
{
    /** 조회 결과 */
    public readonly ResponseResult $result;
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

    public function __construct(array $result)
    {
        $factor = 1;

        $this->result = ResponseResult::from($result['result']);
        // (100)이 붙은 통화 단위는 100단위로 환율이 제공됨
        if (($pos = strpos($result['cur_unit'], '(100)')) !== false) {
            $factor = 0.01;
            $this->cur_unit = substr($result['cur_unit'], 0, $pos);
        } else {
            $this->cur_unit = $result['cur_unit'];
        }
        $this->cur_nm = $result['cur_nm'];
        $this->ttb = floatval($result['ttb']) * $factor;
        $this->tts = floatval($result['tts']) * $factor;
        $this->deal_bas_r = floatval($result['deal_bas_r']) * $factor;
        $this->bkpr = floatval($result['bkpr']) * $factor;
        $this->yy_efee_r = floatval($result['yy_efee_r']) * $factor;
        $this->ten_dd_efee_r = floatval($result['ten_dd_efee_r']) * $factor;
        $this->kftc_deal_bas_r = floatval($result['kftc_deal_bas_r']) * $factor;
        $this->kftc_bkpr = floatval($result['kftc_bkpr']) * $factor;
    }
}
