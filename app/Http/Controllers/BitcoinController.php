<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\PriceReachSubscribeAction;
use App\Actions\Responses\ResponseJsonAction;
use App\Actions\Responses\ResponseRedirectAction;
use App\Actions\Responses\ResponseViewAction;
use App\Actions\SnapshotLoadRecentsAction;

class BitcoinController extends Controller
{
    public function index(SnapshotLoadRecentsAction $recentsLoader): \Illuminate\View\View
    {
        $snapshots = $recentsLoader->execute();

        $snapshotInterval = (float) config('app.snapshot_take_interval_seconds', 30);

        return (new ResponseViewAction('bitcoin', compact('snapshots', 'snapshotInterval')))
            ->execute();
    }

    public function snapshots(SnapshotLoadRecentsAction $loader): \Illuminate\Http\JsonResponse
    {
        $snapshots = $loader->execute();

        return (new ResponseJsonAction($snapshots))
            ->execute();
    }

    public function subscribe(PriceReachSubscribeAction $subscribeAction): \Illuminate\Http\RedirectResponse
    {
        $subscriber = $subscribeAction->execute();

        $success = "Email <b>{$subscriber->email}</b> been subscribed of <b>{$subscriber->price}</b>"
            . ' USD price reach notifications.';

        return (new ResponseRedirectAction('bitcoin.index', $success))
            ->execute();
    }
}
