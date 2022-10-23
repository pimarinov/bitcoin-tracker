<?php

namespace App\Actions;

use GuzzleHttp\Client;

class TickerBitfinexApiRequestAction
{
    public const BITFINEX_API_URL = 'https://api.bitfinex.com/v1/pubticker/BTCUSD';

    public function execute(): array
    {
        $res = (new Client())
            ->request('GET', self::BITFINEX_API_URL, []);

        return json_decode($res->getBody(), true);
    }
}
