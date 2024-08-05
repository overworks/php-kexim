<?php

declare(strict_types=1);

namespace Minhyung\Kexim;

use DateTimeImmutable;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use InvalidArgumentException;

class Kexim
{
    const ENDPOINT_CURRENCY = 'https://www.koreaexim.go.kr/site/program/financial/exchangeJSON';
    const ENDPOINT_INTEREST = 'https://www.koreaexim.go.kr/site/program/financial/interestJSON';
    const ENDPOINT_INTERNATIONAL = 'https://www.koreaexim.go.kr/site/program/financial/internationalJSON';

    const RESULT_CODES = [
        1 => '성공',
        2 => 'DATA 코드 오류',
        3 => '인증코드 오류',
        4 => '일일제한횟수 마감',
    ];

    protected ?Client $client = null;

    public function __construct(private string $authKey)
    {
        //    
    }

    /**
     * 현재환율 API
     * 
     * @link   https://www.koreaexim.go.kr/ir/HPHKIR020M01?apino=2&viewtype=C
     * 
     * @param  string|null  $searchDate
     * @return array
     * @throws \InvalidArgumentException|\Minhyung\Kexim\ApiException
     */
    public function currency($searchDate = null)
    {
        $data = $this->send(self::ENDPOINT_CURRENCY, 'AP01', $searchDate);
        if (! $data) {
            throw new InvalidArgumentException('비영입일 혹은 영업일 11시 이전입니다.');
        }

        // TODO
        foreach ($data as $item) {
            if ($item['result'] !== 1) {
                throw new ApiException(self::RESULT_CODES[$item['result']]);
            }
        }

        return $data;
    }

    /**
     * 대출금리 API
     * 
     * @link   https://www.koreaexim.go.kr/ir/HPHKIR020M01?apino=3&viewtype=C
     * 
     * @param  string|null  $searchDate
     * @return array
     * @throws \Minhyung\Kexim\ApiException
     */
    public function interest($searchDate = null)
    {
        $data = $this->send(self::ENDPOINT_INTEREST, 'AP02', $searchDate);

        // TODO
        foreach ($data as $item) {
            if ($item['result'] !== 1) {
                throw new ApiException(self::RESULT_CODES[$item['result']]);
            }
        }
        return $data;
    }

    /**
     * 국제금리 API
     * 
     * @link   https://www.koreaexim.go.kr/ir/HPHKIR020M01?apino=4&viewtype=C
     * 
     * @param  string|null  $searchDate
     * @return array
     * @throws \Minhyung\Kexim\ApiException
     */
    public function international($searchDate = null)
    {
        $data = $this->send(self::ENDPOINT_INTERNATIONAL, 'AP03', $searchDate);

        // TODO
        
        return $data;
    }

    protected function send(string $endpoint, string $data, $searchDate = null)
    {
        $params = [
            'authkey' => $this->authKey,
            'data' => $data,
        ];
        if ($searchDate) {
            $date = new DateTimeImmutable($searchDate);
            $params['searchdate'] = $date->format('Y-m-d');
        }

        $response = $this->client()->get($endpoint, [RequestOptions::QUERY => $params]);
        $body = (string) $response->getBody();
        return json_decode($body, true);
    }

    protected function client(): Client
    {
        if (! $this->client) {
            $this->client = new Client([
                'http_errors' => true,
                'cookies' => false,
                'headers' => ['Accept' => 'application/json'],
            ]);
        }
        return $this->client;
    }
}
