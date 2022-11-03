<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\PriceReachSubscribeAction;
use App\Http\Requests\SubscribeToPriceReach;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * @internal
 *
 * @covers \App\Actions\PriceReachSubscribeAction
 */
class PriceReachSubscribeActionTest extends TestCase
{
    use DatabaseMigrations;

    public function testPriceReachSubscribeOnceSuccess(): void
    {
        $request = new SubscribeToPriceReach();
        $request->merge(['email' => 'johndowe@test.com', 'price' => 123]);

        $subscriberAction = new PriceReachSubscribeAction($request);

        $subscriber = $subscriberAction->execute();

        $this->assertSame('johndowe@test.com', $subscriber->email);
        $this->assertSame(123, $subscriber->price);
    }

    public function testPriceReachSubscribeTwiceSuccess(): void
    {
        $request = new SubscribeToPriceReach();
        $request->merge(['email' => 'johndowe@test.com', 'price' => 123]);

        $subscriberInitial = (new PriceReachSubscribeAction($request))
            ->execute();

        $request2 = new SubscribeToPriceReach();
        $request2->merge(['email' => 'johndowe@test.com', 'price' => 234]);

        $subscriber = (new PriceReachSubscribeAction($request2))
            ->execute();

        $this->assertSame('johndowe@test.com', $subscriber->email);
        $this->assertSame(234, $subscriber->price);
        $this->assertSame($subscriberInitial->id, $subscriber->id);
    }
}
