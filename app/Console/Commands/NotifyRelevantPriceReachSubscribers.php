<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\PriceReachNotifyAction;
use Illuminate\Console\Command;

class NotifyRelevantPriceReachSubscribers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:relevant-price-reach-subscribers
        {last : Last price}
        {increased : Increased price}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends notification for reached price to subscribers';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $last = (float) $this->argument('last');
        $increased = (float) $this->argument('increased');

        if ($increased <= $last)
        {
            $this->error("The price ({$increased}) is not increase of last ({$last})!");
        }

        $notifiedList = (new PriceReachNotifyAction($last, $increased))
            ->notify();

        $output = $notifiedList
            ? json_encode($notifiedList)
            : '-- price change not matches any subscribers';

        info('-- `notify:relevant-price-reach-subscribers` ' . $output);

        return 0;
    }
}
