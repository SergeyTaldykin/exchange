<?php

namespace App\Console\Commands;

use App\Models\LimitOrder;
use App\Models\Operation;
use App\Models\Pair;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

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

        // TODO  Взять все лимитные ордера / > id
        // TODO  Взять все маркет ордера / > LAST_PROCESSED_ID
        // TODO +объединить два вида ордеров по дате
        // TODO  обработка (Создание записей в filled_orders)
        // TODO
        // TODO  Начисление баланса
        while (true) {
            $limitOrders = LimitOrder::getByPair($pair);
            $marketOrders = Operation::getByPair($pair);

            $ordersLists = $this->ordersMerge($limitOrders, $marketOrders);

            foreach ($ordersLists as $orders) {
                foreach ($orders as $order) {
                    if ($order instanceof LimitOrder) {
                        echo "LIMIT \n";
                    } else {
                        echo get_class($order) . " MARKET \n";
                    }
                }
            }

            usleep(2000000);
            echo "=============\n\n";
        }

        return Command::SUCCESS;
    }

    private function ordersMerge(Collection $collection1, Collection $collection2): array
    {
        $result = [];

        foreach ($collection1 as $item) {
            $result[$item->created_at->getTimestampMs()][] = $item;
        }

        foreach ($collection2 as $item) {
            $result[$item->created_at->getTimestampMs()][] = $item;
        }

        ksort($result);

        return $result;
    }
}
