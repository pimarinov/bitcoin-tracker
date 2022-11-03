<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Snapshot;
use Carbon\Carbon;

class SnapshotLoadRecentsAction
{
    public function execute(?Carbon $timeFrom = null): array
    {
        $starting = $timeFrom ?? Carbon::now()->subminutes(5);

        $snapshots = (new Snapshot())
            ->where('created_at', '>', $starting)
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
