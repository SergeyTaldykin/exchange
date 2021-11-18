<?php

namespace App\Models;

use App\Exchange;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 1;
    public const STATUS_DONE = 2;
    public const STATUS_CANCELLED = 3;

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $guarded = ['id', 'created_at', 'updated_at'];

//    public function user()
//    {
//        return $this->belongsTo(User::class);
//    }
//
//    public function asset()
//    {
//        return $this->belongsTo(Asset::class);
//    }

    public function isBuy(): bool
    {
        return $this->operation_type === Exchange::OPERATION_TYPE_BUY;
    }

    public function isLimit(): bool
    {
        return $this->order_type === Exchange::ORDER_TYPE_LIMIT;
    }

    public function isMarket(): bool
    {
        return $this->order_type === Exchange::ORDER_TYPE_MARKET;
    }

    /**
     * @param Pair $pair
     * @param int $status
     * @return Collection|Order[]
     */
    public static function getByPair(Pair $pair, int $status = self::STATUS_PENDING): Collection
    {
        return self::query()
            ->where('pair_id', $pair->id)
            ->where('status', $status)
            ->get();
    }

    public static function getOrderBook(Pair $pair, int $operationType, int $limit): \Illuminate\Support\Collection
    {
        return self::query()
            ->select('price')
            ->selectRaw('SUM(qty - qty_filled) AS qty')
            ->where('pair_id', $pair->id)
            ->where('status', self::STATUS_PENDING)
            ->where('operation_type', $operationType)
            ->where('order_type', Exchange::ORDER_TYPE_LIMIT)
            ->groupBy('price')
            ->orderBy('price', 'DESC')
            ->limit($limit)
            ->pluck('qty', 'price');
    }
}
