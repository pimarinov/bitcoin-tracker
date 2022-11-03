<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Snapshot;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class SnapshotTakeAction
{
    public const BITFINEX_API_URL = 'https://api.bitfinex.com/v1/pubticker/BTCUSD';

    public function __construct(private Snapshot $snapshot)
    {
    }

    /**
     * @throws \Exception
     */
    public function execute(): Snapshot
    {
        $res = Http::get(self::BITFINEX_API_URL);

        if ($res->failed())
        {
            throw new \Exception('Failed Bitfinex Api Request', Response::HTTP_REQUEST_TIMEOUT);
        }

        $data = json_decode($res->getBody()->getContents(), true);

        $this->snapshot->price = (float) $data['last_price'];
        $this->snapshot->full_response = json_encode($data, 1);

        $this->snapshot->saveOrFail();

        return $this->snapshot;
    }
}
