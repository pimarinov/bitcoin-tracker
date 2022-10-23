<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\SnapshotTakeAction;
use App\Models\Snapshot;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TakeSnapshotActionTest extends TestCase
{
    use DatabaseMigrations;

    public function test_take_snapshot_test(): void
    {
        $snapshot = (new SnapshotTakeAction())->take();

        $this->assertEquals(Snapshot::class, $snapshot::class);
    }
}
