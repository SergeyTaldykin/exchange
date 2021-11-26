<?php

namespace App\Console\Commands;

use App\Exchange;
use App\Models\Order;
use App\Models\Pair;
use Illuminate\Console\Command;

class PairTrading extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trading:pair {pair_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Running trading process for a specific trading pair';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /**
         * @var Pair $pair
         */
        $pair = Pair::find((int)$this->argument('pair_id'));

        $exchange = new Exchange();

        while (true) {
            $exchange->reset();

            foreach (Order::getByPair($pair) as $order) {
                $exchange->addOrder($order);
            }

            echo date('H:i:s') . " =============\n\n";
            usleep(2000000);
        }

        return Command::SUCCESS;
    }
}
