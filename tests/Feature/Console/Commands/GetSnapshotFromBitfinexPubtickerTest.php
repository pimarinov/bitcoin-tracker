<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * @internal
 *
 * @covers \App\Console\Commands\GetSnapshotFromBitfinexPubticker
 */
class GetSnapshotFromBitfinexPubtickerTest extends TestCase
{
    use DatabaseMigrations;

    public function testGetSnapshotFromBitfinexPubtickerSuccess(): void
    {
        $this->artisan('get:snapshot-from-bitfinex-pubticker')
            ->assertExitCode(0);
    }

    public function testGetSnapshotFromBitfinexPubtickerError(): void
    {
        Http::fake(function () {
            return Http::response('Not Found', 404);
        });

        $this->artisan('get:snapshot-from-bitfinex-pubticker')
            ->assertExitCode(1);
    }
}
