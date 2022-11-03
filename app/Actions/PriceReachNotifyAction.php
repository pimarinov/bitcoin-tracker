<?php

declare(strict_types=1);

namespace App\Actions;

use App\Mail\PriceReached;
use App\Models\PriceReachSubscriber;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class PriceReachNotifyAction
{
    public const CACHE_KEY_PREFIX = 'notified-price-reach-subscriber:';

    public function execute(float $last, float $increased): array
    {
        $silenceSeconds = (int) config('app.subscriber_silence_seconds', 3600);

        $notifiedSubscriberIds = [];
        foreach ($this->getPriceReachSubscribers($last, $increased) as $subscriber)
        {
            if (Cache::has(self::CACHE_KEY_PREFIX . $subscriber->id))
            {
                $notifiedSubscriberIds[] = '--skiped:' . $subscriber->id;

                continue;
            }

            Mail::to($subscriber->email)->send(new PriceReached($subscriber));

            Cache::put(self::CACHE_KEY_PREFIX . $subscriber->id, true, $silenceSeconds);

            $notifiedSubscriberIds[] = $subscriber->id;
        }

        return $notifiedSubscriberIds;
    }

    private function getPriceReachSubscribers(float $last, float $increased): Collection
    {
        return (new PriceReachSubscriber())
            ->where('price', '>=', $last)
            ->where('price', '<', $increased)
            ->orderBy('id', 'asc')
            ->get();
    }
}
