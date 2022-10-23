<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\SnapshotLoadRecentsAction;
use App\Models\Snapshot;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SnapshotLoadRescentsActionTest extends TestCase
{
    use DatabaseMigrations;

    public function test_snapshot_load_rescents_success_call(): void
    {
        $from = Carbon::now();

        $result = (new SnapshotLoadRecentsAction($from))
            ->loadRecents();

        $this->assertSame([], $result);
    }

    public function test_snapshot_load_rescents__without_param_success_call(): void
    {
        $snapshots = (new Snapshot())
            ->where('created_at', '>', Carbon::now()->subminutes(5))
            ->get();

        $result = (new SnapshotLoadRecentsAction())
            ->loadRecents();

        $this->assertSame($snapshots->count(), count($result));
    }
}
