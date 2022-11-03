<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\SnapshotTakeAction;
use App\Models\Snapshot;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * @internal
 *
 * @covers \App\Actions\SnapshotTakeAction
 */
class SnapshotTakeActionTest extends TestCase
{
    use DatabaseMigrations;

    private SnapshotTakeAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = app(SnapshotTakeAction::class);
    }

    public function testTakeSnapshotTestSuccess(): void
    {
        $snapshot = $this->action->execute();

        $this->assertEquals(Snapshot::class, $snapshot::class);
    }

    public function testTakeSnapshotError(): void
    {
        Http::fake(function () {
            return Http::response('Not Found', 404);
        });

        $this->expectException(\Exception::class);
        $this->expectExceptionCode(Response::HTTP_REQUEST_TIMEOUT);

        $this->action->execute();
    }
}
