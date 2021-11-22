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

    public static function updateForMakerAndTaker(Order $makerOrder, Order $takerOrder, float $orderQty): void
    {
        $pair = $makerOrder->pair;
        /*if (!$balance = Auth::user()->balances()->where('asset_id', $validated['asset_id'])->first()) {
            $balance = new Balance();
            $balance->asset_id = $validated['asset_id'];
            $balance->volume = 0.0;
            $balance->user()->associate(Auth::user());
        }

        $balance->volume += $validated['volume'];

        $balance->save();*/

        // todo
        dd($makerOrder->user->balances()->where('asset_id', $pair->leftAsset)->get());
        dd($makerOrder->user->balances()->where('asset_id', $a)->get());
//        $makerOrder->user->balance->add($assetId, $qty);
//        $takerOrder->user->balance->add($assetId, $qty);
    }
}
