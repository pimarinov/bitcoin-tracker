<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\SnapshotTakeAction;
use App\Models\Snapshot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

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
    public function handle(SnapshotTakeAction $snapshotAction)
    {
        try
        {
            $snapshot = $snapshotAction->execute();

            $prevSnapshot = Snapshot::where('id', '!=', $snapshot->id)
                ->orderBy('id', 'desc')
                ->first();

            if ($snapshot->price > ($prevSnapshot->price ?? 0))
            {
                Artisan::queue('notify:relevant-price-reach-subscribers', [
                    'last' => $prevSnapshot->price ?? 0,
                    'increased' => $snapshot->price,
                ]);
            }
        }
        catch (\Exception $e)
        {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        $out = "(#{$snapshot->id}, USD{$snapshot->price})";

        $this->info('Ticked: `get:snapshot-from-bitfinex-pubticker` ' . $out);

        return 0;
    }
}
