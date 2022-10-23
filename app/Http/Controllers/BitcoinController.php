<?php

namespace App\Http\Controllers;

use App\Actions\PriceReachSubscribeAction;
use App\Actions\Responses\ResponseJsonAction;
use App\Actions\Responses\ResponseRedirectAction;
use App\Actions\Responses\ResponseViewAction;
use App\Actions\SnapshotLoadRecentsAction;
use App\Http\Requests\SubscribeToPriceReach;

class BitcoinController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $snapshots = (new SnapshotLoadRecentsAction())
            ->loadRecents();

        return (new ResponseViewAction('bitcoin', ['snapshots' => $snapshots]))
            ->execute();
    }

    public function snapshots(): \Illuminate\Http\JsonResponse
    {
        $snapshots = (new SnapshotLoadRecentsAction())->loadRecents();

        return (new ResponseJsonAction($snapshots))
            ->execute();
    }

    public function subscribe(SubscribeToPriceReach $request): \Illuminate\Http\RedirectResponse
    {
        $subscriber = (new PriceReachSubscribeAction($request))
            ->subscribe();

        $success = "Email <b>{$subscriber->email}</b> been subscribed of <b>{$subscriber->price}</b>"
            . ' USD price reach notifications.';

        return (new ResponseRedirectAction('bitcoin.index', $success))
            ->execute();
    }
}
