<?php

namespace App\Console\Commands;

use App\Actions\SnapshotTakeAction;
use Illuminate\Console\Command;

class GetSnapshotFromBitfinexPubticker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:snapshot-from-bitfinex-pubticker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loads snapshot from the pubticker';

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
        // info('Starting: `get:snapshot-from-bitfinex-pubticker`');

        $snapshot = (new SnapshotTakeAction())->take();

        $out = "(#{$snapshot->id}, USD{$snapshot->price})";

        info('Ticked: `get:snapshot-from-bitfinex-pubticker` ' . $out);

        return 0;
    }
}
