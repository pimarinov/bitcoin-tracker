<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GetSnapshotFromBitfinexPubtickerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_get_snapshot_from_bitfinex_pubticker(): void
    {
        $this->artisan('get:snapshot-from-bitfinex-pubticker')
            ->assertExitCode(0);
    }
}
