<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use App\Models\PriceReachSubscriber;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * @internal
 *
 * @covers \App\Console\Commands\NotifyRelevantPriceReachSubscribers
 */
class NotifyRelevantPriceReachSubscribersTest extends TestCase
{
    use DatabaseMigrations;

    public function testNotifyRelevantPriceReachSubscribersSuccess(): void
    {
        $subscriber = new PriceReachSubscriber([
            'email' => 'johndowe@test.com',
            'price' => 124,
        ]);
        $subscriber->saveOrFail();

        $this->artisan('notify:relevant-price-reach-subscribers 123 456')
            ->assertExitCode(0);
    }

    public function testNotifyRelevantPriceReachSubscribersValidates(): void
    {
        $this->artisan('notify:relevant-price-reach-subscribers 123 123')
            ->expectsOutput('The price (123) is not increase of last (123)!');
    }
}
