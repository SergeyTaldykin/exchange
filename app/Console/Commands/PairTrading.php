<?php

namespace App\Console\Commands;

use App\Models\Balance;
use App\Models\FilledOrder;
use App\Models\Order;
use App\Models\Pair;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
            $orders = Order::getByPair($pair);

            $limitOrders = [[], []];

            foreach ($orders as $order) {
                try {
                    DB::beginTransaction();

                    if ($order->isLimit()) {
                        if ($order->isBuy()) {
                            echo "LIMIT BUY $order->id\n";

                            $limitOrders[0][] = $order;
                            //                        foreach ($limitOrders[1] as $sellOrder) {
                            //                            if ($sell) {
                            //
                            //                            }
                            //                        }
                        } else {
                            echo "LIMIT SELL $order->id\n";

                            $limitOrders[1][] = $order;
                        }
                    } else {

                        if ($order->isBuy()) {
                            echo get_class($order) . " MARKET BUY $order->id\n";

                            foreach ($limitOrders[1] as $sellOrder) {
                                $orderQty = $order->qty - $order->qty_filled;
                                $sellOrderQty = $sellOrder->qty - $sellOrder->qty_filled;

                                if ($sellOrderQty >= $orderQty) {
                                    $order->qty_filled = $order->qty;
                                    $order->status = Order::STATUS_DONE;
                                    $order->save();

                                    $sellOrder->qty_filled += $orderQty;
                                    if ($sellOrderQty === $orderQty) {
                                        $sellOrder->status = Order::STATUS_DONE;
                                    }
                                    $sellOrder->save();

                                    FilledOrder::createByMakerAndTaker($sellOrder, $order, $orderQty);
                                    Balance::updateForMakerAndTaker($sellOrder, $order, $orderQty);
                                    break;
                                } else {
                                    $order->qty_filled += $sellOrderQty;
                                    $order->save();

                                    $sellOrder->qty_filled = $sellOrder->qty;
                                    $sellOrder->status = Order::STATUS_DONE;
                                    $sellOrder->save();

                                    FilledOrder::createByMakerAndTaker($sellOrder, $order, $sellOrderQty);
                                    Balance::updateForMakerAndTaker($sellOrder, $order, $orderQty);
                                }
                            }
                        } else {
                            echo get_class($order) . " MARKET SELL $order->id\n";

                        }

                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }

            usleep(2000000);
            echo "=============\n\n";
        }

        return Command::SUCCESS;
    }
}
