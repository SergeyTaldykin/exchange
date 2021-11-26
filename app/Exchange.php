<?php

namespace App;

use App\Models\Balance;
use App\Models\FilledOrder;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

/**
 * TODO 2) Биржа
 *          Подключение через socket и взятие данных (последняя цена, последние операции, лимитные ордера)
 *          Отображение данных
 *          Кнопки buy & sell
 *          buy -
 *          sell - Проверяем баланс
 */
class Exchange
{
    public const OPERATION_COMMISSION = 0.01;

    public const OPERATION_TYPE_BUY = 1;
    public const OPERATION_TYPE_SELL = 2;

    protected array $limitOrdersBuy = [];
    protected array $limitOrdersSell = [];

    public function addOrder(Order $order): void
    {
        try {
            DB::beginTransaction();

            // todo check user free balance
            if ($order->isLimit()) {
                $this->executeLimitOrder($order);
            } else {
                $this->executeMarketOrder($order);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function reset(): void
    {
        $this->limitOrdersBuy = [];
        $this->limitOrdersSell = [];
    }

    protected function executeLimitOrder(Order $limitOrder): void
    {
        if ($limitOrder->isBuy()) {
            echo "LIMIT BUY $limitOrder->id\n";

            foreach ($this->limitOrdersSell as $limitSell) {
                if ($limitSell->price <= $limitOrder->price) {
                    $this->matchOrders($limitOrder, $limitSell);

                    if ($limitOrder->isDone()) {
                        break;
                    }
                } else {
                    break;
                }
            }

            if (!$limitOrder->isDone()) {
                $this->limitOrdersBuy[] = $limitOrder;
                $this->sortOrdersBuy();
            }
        } else {
            echo "LIMIT SELL $limitOrder->id\n";

            foreach ($this->limitOrdersBuy as $limitBuy) {
                if ($limitBuy->price >= $limitOrder->price) {
                    $this->matchOrders($limitOrder, $limitBuy);

                    if ($limitOrder->isDone()) {
                        break;
                    }
                } else {
                    break;
                }
            }

            if (!$limitOrder->isDone()) {
                $this->limitOrdersSell[] = $limitOrder;
                $this->sortOrdersSell();
            }
        }
    }

    protected function executeMarketOrder(Order $marketOrder): void
    {
        echo get_class($marketOrder) . " MARKET "
            . ($marketOrder->isBuy() ? 'BUY' : 'SELL') . " $marketOrder->id\n";

        foreach ($marketOrder->isBuy() ? $this->limitOrdersSell : $this->limitOrdersBuy as $limitOrder) {
            $this->matchOrders($marketOrder, $limitOrder);
            if ($marketOrder->isDone()) {
                break;
            }
        }
    }

    protected function matchOrders(Order $orderA, Order $orderB): void
    {
        $orderAQty = $orderA->qty - $orderA->qty_filled;
        $orderBQty = $orderB->qty - $orderB->qty_filled;

        if ($orderBQty >= $orderAQty) {
            $orderA->qty_filled = $orderA->qty;
            $orderA->status = Order::STATUS_DONE;
            $orderA->save();

            $orderB->qty_filled += $orderAQty;
            if ($orderBQty === $orderAQty) {
                $orderB->status = Order::STATUS_DONE;
            }
            $orderB->save();

            FilledOrder::createByMakerAndTaker($orderB, $orderA, $orderAQty);
            Balance::updateForMakerAndTaker($orderB, $orderA, $orderAQty);
        } else {
            $orderA->qty_filled += $orderBQty;
            $orderA->save();

            $orderB->qty_filled = $orderB->qty;
            $orderB->status = Order::STATUS_DONE;
            $orderB->save();

            FilledOrder::createByMakerAndTaker($orderB, $orderA, $orderBQty);
            Balance::updateForMakerAndTaker($orderB, $orderA, $orderBQty);
        }
    }

    private function sortOrdersBuy(): void
    {
        usort($this->limitOrdersBuy, function($a, $b) {
            return $b->price <=> $a->price;
        });
    }

    private function sortOrdersSell(): void
    {
        usort($this->limitOrdersSell, function($a, $b) {
            return $a->price <=> $b->price;
        });
    }
}
