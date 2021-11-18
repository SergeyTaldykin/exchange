<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public static function updateForMakerAndTaker(Order $sellOrder, Order $order, float $orderQty): void
    {
        $sellOrder->user->balance->add($assetId, $qty);
        $order->user->balance->add($assetId, $qty);
    }
}
