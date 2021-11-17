<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

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

    public static function getByPair(Pair $pair): Collection
    {
        return self::query()
            ->where('pair_id', $pair->id)
            ->get();
    }
}
