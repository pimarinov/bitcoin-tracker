<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\Requests\SubscribeToPriceReach;
use App\Models\PriceReachSubscriber;

class PriceReachSubscribeAction
{
    public function __construct(private SubscribeToPriceReach $request)
    {
    }

    public function execute(): PriceReachSubscriber
    {
        $subscriber = PriceReachSubscriber::firstOrNew(['email' => $this->request->email]);

        $subscriber->price = $this->request->price;

        $subscriber->saveOrFail();

        return $subscriber;
    }
}
