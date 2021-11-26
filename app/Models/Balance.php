<?php

namespace App\Models;

use App\Exchange;
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

        if ($makerOrder->isBuy()) {
            self::switchVolume($makerOrder->user, $takerOrder->user, $pair->leftAsset, $orderQty);
            self::switchVolume($takerOrder->user, $makerOrder->user, $pair->rightAsset, $orderQty * $makerOrder->price);
        } else {
            self::switchVolume($makerOrder->user, $takerOrder->user, $pair->rightAsset, $orderQty * $makerOrder->price);
            self::switchVolume($takerOrder->user, $makerOrder->user, $pair->leftAsset, $orderQty);
        }
    }

    protected static function switchVolume(User $user1, User $user2, Asset $asset, float $volume): void
    {
        $balance1 = self::getByUserAndAsset($user1, $asset);
        $balance1->volume += $volume * (1 - Exchange::OPERATION_COMMISSION);
        $balance1->save();

        $balance2 = self::getByUserAndAsset($user2, $asset);
        $balance2->volume -= $volume;
        $balance2->save();
    }

    public static function getByUserAndAsset(User $user, Asset $asset): Balance
    {
        if (!$balance = $user->balances()->where('asset_id', $asset->id)->first()) {
           $balance = new Balance();
           $balance->asset_id = $asset->id;
           $balance->volume = 0.0;
           $balance->user()->associate($user);
        }

        return $balance;
    }

    public function getFreeVolume(): float
    {
        $volumeInSells = Order::query() // todo move to Balance class
                ->selectRaw('SUM((qty - qty_filled)) AS realQty')
                ->where('user_id', $this->user->id)
                ->whereIn('pair_id', function($query) {
                    $query->select('id')
                        ->from(with(new Pair)->getTable())
                        ->where('left_asset_id', $this->asset->id);
                })
                ->where('status', Order::STATUS_PENDING)
                ->where('operation_type', Exchange::OPERATION_TYPE_SELL)->first()->realQty ?? 0.0;

        $volumeInBuys = Order::query()
                ->selectRaw('SUM((qty - qty_filled) * price) AS realQty')
                ->where('user_id', $this->user->id)
                ->whereIn('pair_id', function($query) {
                    $query->select('id')
                        ->from(with(new Pair)->getTable())
                        ->where('right_asset_id', $this->asset->id);
                })
                ->where('status', Order::STATUS_PENDING)
                ->where('operation_type', Exchange::OPERATION_TYPE_BUY)->first()->realQty ?? 0.0;

        return $this->volume - $volumeInBuys - $volumeInSells;
    }

    public static function format(float $value): string
    {
        return sprintf('%.8f', $value);
    }
}
