<?php

declare(strict_types=1);

namespace Minhyung\Kexim;

use DateTimeImmutable;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use LogicException;
use Minhyung\Kexim\Exceptions\InvalidDateException;

class Kexim
{
    const DOMAIN = 'oapi.koreaexim.go.kr';

    /** @deprecated Use ENDPOINT_EXCHANGE instead */
    const ENDPOINT_CURRENCY = 'https://'.self::DOMAIN.'/site/program/financial/exchangeJSON';

    const ENDPOINT_EXCHANGE = 'https://'.self::DOMAIN.'/site/program/financial/exchangeJSON';
    const ENDPOINT_INTEREST = 'https://'.self::DOMAIN.'/site/program/financial/interestJSON';
    const ENDPOINT_INTERNATIONAL = 'https://'.self::DOMAIN.'/site/program/financial/internationalJSON';

    private string $authKey;
    private array $config;
    private ?Client $client = null;

    /**
     * Create a new Kexim instance.
     * 
     * @param  string  $authKey
     * @param  array   $config   Guzzle default options
     * @return void
     */
    public function __construct(string $authKey, array $config = [])
    {
        $this->authKey = $authKey;
        $this->config = $config;
    }

    /**
     * 현재환율 API
     * 
     * @link   https://www.koreaexim.go.kr/ir/HPHKIR020M01?apino=2&viewtype=C
     * 
     * @deprecated Use exchange() instead
     * @param  string|null  $searchDate
     * @return array
     * @throws \Minhyung\Kexim\Exceptions\InvalidDateException|\Minhyung\Kexim\Exceptions\ApiException
     */
    public function currency($searchDate = null)
    {
        return $this->exchange($searchDate);
    }

    /**
     * 현재환율 API
     * 
     * @link   https://www.koreaexim.go.kr/ir/HPHKIR020M01?apino=2&viewtype=C
     * 
     * @param  string|null  $searchDate
     * @return \Minhyung\Kexim\Exchange
     * @throws \Minhyung\Kexim\Exceptions\InvalidDateException
     * @throws \Minhyung\Kexim\Exceptions\ApiException
     */
    public function exchange($searchDate = null)
    {
        $response = $this->send(self::ENDPOINT_EXCHANGE, 'AP01', $searchDate);

        $result = new Exchange($response);

        return $result;
    }

    /**
     * 대출금리 API
     * 
     * @link   https://www.koreaexim.go.kr/ir/HPHKIR020M01?apino=3&viewtype=C
     * 
     * @param  string|null  $searchDate
     * @return \Minhyung\Kexim\Interest
     * @throws \Minhyung\Kexim\Exceptions\InvalidDateException
     * @throws \Minhyung\Kexim\Exceptions\ApiException
     */
    public function interest($searchDate = null)
    {
        $response = $this->send(self::ENDPOINT_INTEREST, 'AP02', $searchDate);

        $result = new Interest($response);

        return $result;
    }

    /**
     * 국제금리 API
     * 
     * @link   https://www.koreaexim.go.kr/ir/HPHKIR020M01?apino=4&viewtype=C
     * 
     * @param  string|null  $searchDate
     * @return \Minhyung\Kexim\International
     * @throws \LogicException
     * @throws \Minhyung\Kexim\Exceptions\InvalidDateException
     * @throws \Minhyung\Kexim\Exceptions\ApiException
     */
    public function international($searchDate = null)
    {
        $data = $this->send(self::ENDPOINT_INTERNATIONAL, 'AP03', $searchDate);

        return new International($data);
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
        $responseBody = $response->getBody()->getContents();
        if (! $responseBody) {
            throw new InvalidDateException('Invalid date: '.$searchDate);
        }
        
        return json_decode($responseBody, true);
    }

    protected function client(): Client
    {
        return $this->client ??= new Client($this->config);
    }
}
