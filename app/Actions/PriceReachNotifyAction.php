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

    public function __construct(private float $last, private float $increased)
    {
    }

    public function notify(): array
    {
        $silenceSeconds = (int) config('app.subscriber_silence_seconds');

        $matchingSubscribers = $this->getPriceReachSubscribers();

        $notifiedSubscriberIds = [];
        foreach ($matchingSubscribers as $subscriber)
        {
            if (Cache::has(self::CACHE_KEY_PREFIX . $subscriber->id))
            {
                $notifiedSubscriberIds[] = '--skiped:' . $subscriber->id;

                continue;
            }
            $notifiedSubscriberIds[] = $this->sendEmail($subscriber, $silenceSeconds);
        }

        return $notifiedSubscriberIds;
    }

    private function getPriceReachSubscribers(): Collection
    {
        return (new PriceReachSubscriber())
            ->where('price', '>=', $this->last)
            ->where('price', '<', $this->increased)
            ->orderBy('id', 'asc')
            ->get();
    }

    private function sendEmail(PriceReachSubscriber $subscriber, int $seconds): int
    {
        Mail::to($subscriber->email)->send(new PriceReached($subscriber));

        Cache::put(self::CACHE_KEY_PREFIX . $subscriber->id, true, $seconds);

        return $subscriber->id;
    }
}
