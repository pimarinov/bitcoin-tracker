<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\SnapshotLoadRecentsAction;
use App\Models\Snapshot;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * @internal
 *
 * @covers \App\Actions\SnapshotLoadRecentsAction
 */
class SnapshotLoadRescentsActionTest extends TestCase
{
    use DatabaseMigrations;

    private SnapshotLoadRecentsAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = app(SnapshotLoadRecentsAction::class);
    }

    public function testSnapshotLoadRescentsEmptySuccessCall(): void
    {
        $from = Carbon::now();

        $result = $this->action->execute($from);

        $this->assertSame([], $result);
    }

    public function testSnapshotLoadRescentsWithoutParamSuccessCall(): void
    {
        $snapshots = (new Snapshot())
            ->where('created_at', '>', Carbon::now()->subminutes(5))
            ->get();

        $result = $this->action->execute();

        $this->assertSame($snapshots->count(), count($result));
    }

    public function testSnapshotLoadRescentsWithDataSuccessCall(): void
    {
        $first = (new Snapshot(['price' => 123, 'full_response' => '[]']))
            ->saveOrFail();

        $second = (new Snapshot(['price' => 123, 'full_response' => '[]']))
            ->save();

        $snapshots = (new Snapshot())
            ->where('created_at', '>', Carbon::now()->subminutes(5))
            ->get();

        $result = $this->action->execute();

        $this->assertSame($snapshots->count(), count($result));
    }
}
