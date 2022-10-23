<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\TickerBitfinexApiRequestAction;
use App\DataTransferObjects\SnapshotValues;
use App\Models\Snapshot;
use Illuminate\Support\Facades\Artisan;

class SnapshotTakeAction
{
    public function take(): Snapshot
    {
        $snapshotValues = $this->getValuesFromApi();

        $snapshot = new Snapshot([
            'price' => $snapshotValues->price,
            'full_response' => json_encode($snapshotValues->full_response, 1),
        ]);

        $snapshot->saveOrFail();

        $prevSnapshot = $this->getLastSnapshot($snapshot->id);

        if ($snapshot->price > ($prevSnapshot->price ?? 0))
        {
            Artisan::queue('notify:relevant-price-reach-subscribers', [
                'last' => $prevSnapshot->price ?? 0,
                'increased' => $snapshot->price,
            ]);
        }

        return $snapshot;
    }

    private function getValuesFromApi(): SnapshotValues
    {
        $apiCaller = new TickerBitfinexApiRequestAction();
        $data = $apiCaller->execute();

        return new SnapshotValues((float) $data['last_price'], $data);
    }

    private function getLastSnapshot(int $newId)
    {
        return Snapshot::where('id', '!=', $newId)
            ->orderBy('id', 'desc')
            ->first();
    }
}
