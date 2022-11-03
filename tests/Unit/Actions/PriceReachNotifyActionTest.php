<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\PriceReachNotifyAction;
use App\Models\PriceReachSubscriber;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * @internal
 *
 * @covers \App\Actions\PriceReachNotifyAction
 */
class PriceReachNotifyActionTest extends TestCase
{
    use DatabaseMigrations;

    private PriceReachNotifyAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new PriceReachNotifyAction();
    }

    public function testPriceReachNotifyActionNoSubscribersSuccess(): void
    {
        $result = $this->action->execute(1, 2);

        $this->assertSame([], $result);
    }

    public function testPriceReachNotifyActionOneMatchingOneSkippedSubscriberSuccess(): void
    {
        $subscriber1 = $this->registerSubscriber('johndowe+1@tes.com', 10);
        $subscriber2 = $this->registerSubscriber('johndowe+2@tes.com', 20);

        $this->action->execute(10, 20);

        $result = $this->action->execute(10, 30);

        $this->assertTrue(in_array('--skiped:' . $subscriber1->id, $result));
        $this->assertTrue(in_array($subscriber2->id, $result));
        $this->assertEquals(2, count($result));
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
