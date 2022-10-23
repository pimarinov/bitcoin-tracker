<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use App\Models\PriceReachSubscriber;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class NotifyRelevantPriceReachSubscribersTest extends TestCase
{
    use DatabaseMigrations;

    public function test_notify_relevant_price_reach_subscribers_success(): void
    {
        $subscriber = new PriceReachSubscriber([
            'email' => 'johndowe@test.com',
            'price' => 124,
        ]);
        $subscriber->saveOrFail();

        $this->artisan('notify:relevant-price-reach-subscribers 123 456')
            ->assertExitCode(0);
    }

    public function test_notify_relevant_price_reach_subscribers_validates(): void
    {
        $this->artisan('notify:relevant-price-reach-subscribers 123 123')
            ->expectsOutput('The price (123) is not increase of last (123)!');
    }
}
