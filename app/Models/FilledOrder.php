<?php

namespace App\Models;

use App\Exchange;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilledOrder extends Model
{
    use HasFactory;

    protected $dateFormat = 'Y-m-d H:i:s.u';

    public function makerOrder()
    {
        return $this->belongsTo(Order::class);
    }

    public function isBuy(): bool
    {
        return $this->makerOrder->operation_type === Exchange::OPERATION_TYPE_SELL;
    }

    public static function createByMakerAndTaker(Order $makerOrder, Order $takerOrder, float $qty): void
    {
        $filledOrder = new self;
        $filledOrder->pair_id = $makerOrder->pair_id;
        $filledOrder->maker_order_id = $makerOrder->id;
        $filledOrder->taker_order_id = $takerOrder->id;
        $filledOrder->qty = $qty;
        $filledOrder->save();
    }
}
