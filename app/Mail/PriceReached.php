<?php

namespace App\Mail;

use App\Models\PriceReachSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PriceReached extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(private PriceReachSubscriber $subscriber)
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.price-reached', ['subscriber' => $this->subscriber])
            ->subject(config('app.name') . " — The subscribe prace was reached");
    }
}
