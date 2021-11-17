<?php

namespace App\Models;

use App\Exchange;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LimitOrder extends Model
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

    public static function getByPair(Pair $pair, int $status = self::STATUS_PENDING): Collection
    {
        return self::query()
            ->where('pair_id', $pair->id)
            ->where('status', $status)
            ->get();
    }
}
