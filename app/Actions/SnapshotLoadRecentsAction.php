<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Snapshot;
use Carbon\Carbon;
use stdClass as JsonDataRow;

class SnapshotLoadRecentsAction
{
    public function __construct(private Snapshot $recentSnapshots)
    {
    }

    public function execute(?Carbon $timeFrom = null): array
    {
        $starting = $timeFrom ?? Carbon::now()->subminutes(5);

        $snapshots = $this->recentSnapshots
            ->where('created_at', '>', $starting)
            ->get();

        $results = [];
        foreach ($snapshots as $snapshot)
        {
            $current = new JsonDataRow();
            $current->x = $snapshot->created_at->getPreciseTimestamp(3);
            $current->y = (float) $snapshot->price;

            $results[] = $current;
        }

        return $results;
    }
}
