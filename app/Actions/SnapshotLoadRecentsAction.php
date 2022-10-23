<?php

namespace App\Actions;

use App\Models\Snapshot;
use Carbon\Carbon;

class SnapshotLoadRecentsAction
{
    private ?Carbon $starting;

    public function __construct(?Carbon $timeFrom = null)
    {
        $this->starting = $timeFrom ?? Carbon::now()->subminutes(5);
    }

    public function loadRecents(Carbon $timeFrom = null): array
    {
        $snapshots = (new Snapshot())
            ->where('created_at', '>', $this->starting)
            ->get();

        $results = [];
        foreach ($snapshots as $snapshot)
        {
            $results[] = (object) [
                'x' => $snapshot->created_at->getPreciseTimestamp(3),
                'y' => (float) $snapshot->price, ];
        }

        return $results;
    }
}
