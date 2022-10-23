<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use Tests\TestCase;
use App\Actions\PriceReachSubscribeAction;
use App\Http\Requests\SubscribeToPriceReach;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PriceReachSubscribeActionTest extends TestCase
{
    use DatabaseMigrations;

    public function test_price_reach_subscribe_once_success(): void
    {
        $request = new SubscribeToPriceReach();
        $request->merge(['email' => 'johndowe@test.com', 'price'=> 123]);

        $subscriberAction = new PriceReachSubscribeAction($request);

        $subscriber = $subscriberAction->subscribe();

        $this->assertSame('johndowe@test.com', $subscriber->email);
        $this->assertSame(123, $subscriber->price);
    }

    public function test_price_reach_subscribe_twice_success(): void
    {
        $request = new SubscribeToPriceReach();
        $request->merge(['email' => 'johndowe@test.com', 'price'=> 123]);

        $subscriberInitial = (new PriceReachSubscribeAction($request))
            ->subscribe();

        $request2 = new SubscribeToPriceReach();
        $request2->merge(['email' => 'johndowe@test.com', 'price'=> 234]);

        $subscriber = (new PriceReachSubscribeAction($request2))
            ->subscribe();

        $this->assertSame('johndowe@test.com', $subscriber->email);
        $this->assertSame(234, $subscriber->price);
        $this->assertSame($subscriberInitial->id, $subscriber->id);
    }
}
