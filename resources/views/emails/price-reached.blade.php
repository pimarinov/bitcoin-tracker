@component('mail::message')



The price of BTC has exceeded the limit of <b>{{ $subscriber->price }}</b> USD.

<br>

Thanks,<br>
{{ config('app.name') }}

@endcomponent
