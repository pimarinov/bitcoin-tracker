<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\PriceReachNotifyAction;
use App\Models\PriceReachSubscriber;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PriceReachNotifyActionTest extends TestCase
{
    use DatabaseMigrations;

    public function test_price_reach_notify_action_no_subscribers_success(): void
    {
        $result = (new PriceReachNotifyAction(1, 2))
            ->notify();

        $this->assertSame([], $result);
    }

    public function test_price_reach_notify_action_one_matching_subscriber_success(): void
    {
        $subscriber1 = $this->registerSubscriber('johndowe+1@tes.com', 10);
        $subscriber2 = $this->registerSubscriber('johndowe+2@tes.com', 20);

        $result = (new PriceReachNotifyAction(10, 20))
            ->notify();

        $this->assertTrue(in_array($subscriber1->id, $result));
    }

    public function test_price_reach_notify_action_one_matching_one_skipped_subscriber_success(): void
    {
        $subscriber1 = $this->registerSubscriber('johndowe+1@tes.com', 10);
        $subscriber2 = $this->registerSubscriber('johndowe+2@tes.com', 20);

        (new PriceReachNotifyAction(10, 20))
            ->notify();

        $result = (new PriceReachNotifyAction(10, 30))
            ->notify();

        $this->assertTrue(in_array('--skiped:' . $subscriber1->id, $result));
        $this->assertTrue(in_array($subscriber2->id, $result));
    }

    private function registerSubscriber(string $email, float $price): PriceReachSubscriber
    {
        $subscriber = new PriceReachSubscriber([
            'email' => $email,
            'price' => $price,
        ]);
        $subscriber->saveOrFail();

        return PriceReachSubscriber::find($subscriber->id);
    }
}
